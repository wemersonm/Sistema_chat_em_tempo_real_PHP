var chat = {
	groups:[],
	activeGroup:0,
	lastTime:'',
	msgRequest:null,
	userRequest:null,
	

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
				messages:[],
				users:[]
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
	removeGroup:function(id){
		for(let i in this.groups){
			if(this.groups[i].id == id){
				const pos = i;
				this.groups.splice(i,1);
			}
		}
		if(this.activeGroup == id){
			if(this.groups.length > 0){
				this.setActiveGroup(this.groups[0].id);		
			}else{
				this.activeGroup = 0;
				chat.closeLastGroup();
			}
		}
		
		this.updateGroupView();
		if(this.msgRequest != null){
			 controller.abort();
		}
	},
	closeLastGroup(){

		const url = BASE_URL+'ajax/clearLastGroup';
		fetch(url,{
			method:'GET',
		}).then(response=>response.text())
		.then(data=>{
			 json = JSON.parse(data);
			if(json.status == '1'){					
			}
			else{
				window.location.href = BASE_URL+'login'
			}
		}).catch(e=>{
				
		})
		.finally(f=>{
			
		});

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
			html+='<div class="group_name">'+this.groups[i].name+'</div>';
			html+='<div class="group_close">X</div>'
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

		this.showUserList();
	},
	showUserList:function(){
		if(this.activeGroup != 0){
			let users = [];
			for(let i in this.groups){
				if(this.activeGroup == this.groups[i].id){
					users = this.groups[i].users;
				}
			}

			let html = '';
			for(let j in users){
				html+='<li>'+users[j]+'</li>';
			}
			document.querySelector('.users-online ul').innerHTML = html;
		}else{
			document.querySelector('.users-online ul').innerHTML = '';
		}
	},
	showMessages:function(){
		document.querySelector('.message').innerHTML = '';
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
				if(msgs[i].msg_type == 'text'){
					html+="<p>"+msgs[i].msg+"</p>"
				}else if(msgs[i].msg_type == 'img'){
					html+='<img src="'+BASE_URL+'assets/images/photos/'+msgs[i].msg+'">';
				}
				html+="</div>";
				html+="</div>";
		
				document.querySelector('.message').innerHTML+=html;
			
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
					if(data.error == '1'){
						alert(data.errorMsg);
					}
				}else{
					window.location.href = BASE_URL+'login';
				}
			})
			.catch(e=> console.log(e)); 

		}
	},
	sendPhoto:function(photo){
		if(this.activeGroup != 0){
			const formData = new FormData();
			formData.append('photo',photo);
			formData.append('id_group',this.activeGroup);

			var xhr = new XMLHttpRequest();
			xhr.open('POST', BASE_URL+'ajax/add_photo');

			xhr.addEventListener('load', function(event) {
				var response = event.target.response;
			  // Processar a resposta do servidor aqui
				// event.taget == promisse
				response = JSON.parse(response);
				console.log(response);
				if(response.status == '1'){
					if(response.error == '1'){
						alert(response.errorMsg);
					}
				}else{
					window.location.href = BASE_URL+'login';
				}
			});
			xhr.upload.addEventListener('progress', function(event) {
	   			var progress = (event.loaded / event.total)*100;
			    // Atualizar a barra de progresso aqui
			    if(progress > 0){
			    	document.querySelector('.progress').style.display = 'block';
			    	document.querySelector('.progress-bar').style.width = progress + '%';


			    }
			    setTimeout(() => { document.querySelector('.progress-bar').style.width = 0 + '%';  }, 4000);
			    // if(progress >=100){
			    // 	document.querySelector('.progress').style.display = 'none';
			    // 	document.querySelector('.progress-bar').style.width = '0%';
			    // }

	 		},false);
	 
	 		xhr.send(formData);

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
					msg:dataMsg.msg,
					msg_type:dataMsg.msg_type
				});
			}
		}
	},
	updateUserList:function(list,id_group){
		for(let i in this.groups){
			if(this.groups[i].id == id_group){
				this.groups[i].users = list;
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
					
			})
			.finally(f=>{
				chat.chatActivity();
			});
			
		}else{
			setTimeout(function(){
				chat.chatActivity();
			},1000)
		}
		
	},
	userListActivity:function() {
		let allGroups = this.getGroups();
		let groups= [];
		for(let i in allGroups){
			groups.push(allGroups[i].id); 
		}
		const url = BASE_URL+'ajax/getUserList?groups=' + encodeURIComponent(groups);
		
		if(groups.length > 0){
			// controller = new AbortController();
			this.userRequest = fetch(url,{
				method:'GET',
				// signal: controller.signal
			}).then(response=>response.text())
			.then(data=>{
				 json = JSON.parse(data);
				if(json.status == '1'){
					for(let i in json.users){
						chat.updateUserList(json.users[i],i);
					}
					chat.showUserList();					
				}
				else{
					window.location.href = BASE_URL+'login'
				}
			}).catch(e=>{
					
			})
			.finally(f=>{
				setTimeout(function(){
				chat.userListActivity();
				},5000)
			});
			
		}else{
			setTimeout(function(){
				chat.userListActivity();
			},1000)
		}
	}


};
