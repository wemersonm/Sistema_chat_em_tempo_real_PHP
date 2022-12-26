var chat = {
	groups:[],

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
				name:name
			});
		}
		this.updateGroupView();
	},
	getGroups:function(){
			return this.groups;
		},
	updateGroupView: function(){
		let html = '';
		for(let i in this.groups){
			html+='<li>'+this.groups[i].name+'</li>';
		}
		document.querySelector('.content-header nav ul').innerHTML = html;
	}

}