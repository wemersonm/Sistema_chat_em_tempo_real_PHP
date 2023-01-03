var chat = {
	groups:[],
	activeGroup:0,
	lastTime:'',
	msgRequest:null,
	

	setGroup: function(id,name){
		let found = false;

		for(let i in this.groups){
			if(this.groups[i].id == id){
				found = true;
			}
		}
		if(found == false){
			this.groups.push({
				id:id,
				name:name,
				messages:[]
			});
		}
		if(this.groups.length == 1){
			this.setActiveGroup(id);
		}
		this.updateGroupView();

		if(this.msgRequest != null){
			 controller.abort();
		}
	},
	getGroups:function(){
			return this.groups;
		},
	loadGroupList:function(ajaxCallback){

		fetch(BASE_URL+'ajax/get_groups',{
			method:'GET'
		}).then(response => response.json())
		.then(data => {
			if(data.status == '1'){
				ajaxCallback(data);
			}else{
				window.location.href = BASE_URL+'login';
			}
		})
		.catch(e=> console.log(e)); 
	},
	addNewGroup:function(nameGroup,ajaxCallback){
		const formData = new FormData();
		formData.append('name',nameGroup);
		fetch(BASE_URL+'ajax/add_group',{
			method:'POST',
			body:formData
		}).then(response => response.json())
		.then(data => {
			if(data.status == '1'){
				ajaxCallback(data);
			}else{
				window.location.href = BASE_URL+'login';
			}
		})
		.catch(e=> console.log(e)); 
	},
	updateGroupView: function(){
		let html = '';
		for(let i in this.groups){
			html+='<li data-id="'+this.groups[i].id+'">';
			html+='<div class="group_close">X</div>'
			html+='<div class="group_name">'+this.groups[i].name+'</div>';
			html+='</li>';

		}
		document.querySelector('.content-header nav ul').innerHTML = html;
		this.loadConversation();
	}, 

	setActiveGroup:function(id){
		this.activeGroup = id;
		this.loadConversation();

	},

	getActiveGroup:function(){
		return this.activeGroup;
	},
	loadConversation:function(){
		if(this.activeGroup != 0){
			const ul = document.querySelector('.content-header nav ul');
			const lis = ul.querySelectorAll('li');

			lis.forEach(e=>{
	    		e.classList.remove('active');
	    		
	    		if(e.getAttribute('data-id') == this.activeGroup){
	    			e.classList.add('active');
	    		}
			});
		}		
		this.showMessages();
	},
	showMessages:function(){
		document.querySelector('.content-body').innerHTML = '';
		if(this.activeGroup != 0){
			var msgs = [];

			for(let i in this.groups){
				if(this.groups[i].id == this.activeGroup){
					msgs = this.groups[i].messages;
				}
			}
			for(let i in msgs){
				let html="<div class='message'>";
				html+="<div class='message-header'>";
				html+="<span>"+msgs[i].senderName+"</span>";
				html+="<span>"+msgs[i].senderDate+"</span>";
				html+="</div>";
				html+="<div class='message-body'>";
				html+="<p>"+msgs[i].msg+"</p>"
				html+="</div>";
				html+="</div>";
		
				document.querySelector('.content-body').innerHTML+=html;
			
			}
				
			
		}
	},

	sendMessage:function(msg){
		if(msg.length > 0 && this.activeGroup != 0){
			const formData = new FormData();
			formData.append('id_group',this.activeGroup);
			formData.append('msg',msg);
			fetch(BASE_URL+'ajax/add_messages',{
				method:'POST',
				body:formData
			}).then(response => response.json())
			.then(data => {
				if(data.status == '1'){
					if(data.errorMsg == '1'){
						alert(errorMsg);
					}
				}else{
					window.location.href = BASE_URL+'login';
				}
			})
			.catch(e=> console.log(e)); 

		}
	},
	updateLasTime:function(last_time){
		this.lastTime = last_time;
	},
	insertMessage:function(dataMsg){
		for(let i in this.groups){
			if(this.groups[i].id == dataMsg.id_group){
				let date_msg = dataMsg.date_msg.split(' ');
				date_msg = date_msg[0];


				this.groups[i].messages.push({
					id:dataMsg.id,
					senderId:dataMsg.id_user,
					senderName:dataMsg.user_name,
					senderDate:date_msg,
					msg:dataMsg.msg

				});
			}
		}
	},
	chatActivity:function(){
		let allGroups = this.getGroups();
		let groups= [];
		for(let i in allGroups){
			groups.push(allGroups[i].id); 
		}
		const last_time= this.lastTime;

		const url = BASE_URL+'ajax/getMessages?last_time=' + encodeURIComponent(JSON.stringify(last_time)) 
		+ '&groups=' + encodeURIComponent(groups);
		
		if(groups.length > 0){
			controller = new AbortController();
			this.msgRequest = fetch(url,{
				method:'GET',
				signal: controller.signal
			}).then(response=>response.text())
			.then(data=>{
				 json = JSON.parse(data);
				if(json.status == '1'){
					chat.updateLasTime(json.last_time);
					for(let i in json.msgs){
						chat.insertMessage(json.msgs[i]);
					}
					chat.showMessages();					
				}
				else{
					window.location.href = BASE_URL+'login'
				}
			}).catch(e=>{
				console.log(e);		
			})
			.finally(f=>{
				chat.chatActivity();
			});
			
		}else{
			setTimeout(function(){
				chat.chatActivity();
			},1000)
		}
		
	}


};