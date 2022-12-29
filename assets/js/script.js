const btn = document.querySelector(".add_tab");
const bodyH = document.body.clientHeight;
console.log(bodyH);
const div = document.querySelector(".content-body");
div.scrollTop = div.scrollHeight;


btn.addEventListener('click', e =>{
	const modal_bg = document.querySelector('.modal_bg');
	modal_bg.style.display = 'block';

	// O que vai aparecer no modal : START
	let html = '<h1>Escolha uma sala de bate-papo</h1>';
	html+='<div id="group-list">Carregando ....</div>';
	html+='<hr>';
	html+='<button onclick="addGroupModal();">Criar nova sala</button>';
	html+='<button onclick="fecharModal();">Fechar janela</button>';
	// O que vai aparecer no modal : END

	// setar no modal
	document.querySelector('.modal_area').innerHTML = html;

	chat.loadGroupList(function(json){ 
		
		let html = '';
		for(let i in json.listGroups){
			html+='<button data-id="'+json.listGroups[i].id+'">'+json.listGroups[i].name+'</button>';
		}
		//funcao que mostra e requisita os grupos no banco de dados(via php)
		document.querySelector('#group-list').innerHTML = html;

		const Lista = document.querySelector('#group-list').querySelectorAll('button');
		Lista.forEach(button => {
			button.addEventListener('click', event => {
		    	groupId = button.getAttribute('data-id');
		    	groupName = button.textContent;
		    	chat.setGroup(groupId,groupName);
		    	modal_bg.style.display = 'none';	
			});
		});
		
	});  
});

	const ul = document.querySelector('.content-header nav ul');
	ul.addEventListener('click', e=>{
		if(e.target.tagName == 'LI'){
			const id = e.target.getAttribute('data-id');
			chat.setActiveGroup(id);
		}
	});

	document.querySelector('#sender_input').addEventListener('keyup',e=>{
		if(e.keyCode == '13'){
		 let msg = document.querySelector('#sender_input').value;
		 console.log(msg);
		 document.querySelector('#sender_input').value = '';
		 chat.sendMessage(msg);
		}

	});
	document.querySelector('#btn-send').addEventListener('click',e=>{
		 let msg = document.querySelector('#sender_input').value;
		 document.querySelector('#sender_input').value = '';
		 chat.sendMessage(msg);
	});


		
	








	

function fecharModal(){
	document.querySelector('.modal_bg').style.display = 'none';
}

function addGroupModal(){
	let html = '<h1>Criar nova sala</h1>';
	html+='Nome<br><input type="text" id="nameGroup"><br>';
	html+='<button id="buttonNewGroup">Cadastrar</button>';
	html+='<hr><br><button onclick="fecharModal();">Fechar janela</button>';
	document.querySelector('.modal_area').innerHTML = html;

	document.querySelector('#buttonNewGroup').addEventListener('click',e=>{
		const nameGroup = document.querySelector('#nameGroup').value;
		if(nameGroup){
			chat.addNewGroup(nameGroup,function(json){
				if(json.error == '0'){
					document.querySelector(".add_tab").click();
				}else{
					alert(json.errorMsg);
				}
			});
		}

	});


}