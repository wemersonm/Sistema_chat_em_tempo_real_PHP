var chat = {
	groups:[],
	activeGroup:0,

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
				messages:[
				{id:1,senderId:1,senderName:"WM",senderDate:'00:02',msg:"Ankara Messi"},
				{id:2,senderId:1,senderName:"WM",senderDate:'00:04',msg:"Gol"+name}
				
				]
			});
		}
		if(this.groups.length == 1){
			this.setActiveGroup(id);
		}
		this.updateGroupView();
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
			html+='<li data-id="'+this.groups[i].id+'">'+this.groups[i].name+'</li>';
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
		document.querySelector('.message').innerHTML = '';
		if(this.activeGroup != 0){
			var msgs = [];

			for(let i in this.groups){
				if(this.groups[i].id == this.activeGroup){
					msgs = this.groups[i].messages;
				}
			}
			for(let i in msgs){
				let html = '';
				html+='<div class="message">';
				html+='<div class="message-header">';
				html+='<span>'+msgs[i].senderName+'</span>';
				html+='<span>'+msgs[i].senderDate+'</span>';
				html+='</div>';
				html+='<div class="message-body">';
				html+='<p>'+msgs[i].msg+'</p>'
				html+='</div>';
				html+='</div>';
				document.querySelector('.message').innerHTML = html;
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
	}

};