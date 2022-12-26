const btn = document.querySelector(".add_tab");

btn.addEventListener('click', e =>{
	const modal_bg = document.querySelector('.modal_bg');
	modal_bg.style.display = 'block';

	let html = '<h1>Escolha uma sala de bate-papo</h1>';
	html+='<button onclick="fecharModal();">Fechar janela</button>';
	document.querySelector('.modal_area').innerHTML = html;
	

});

function fecharModal(){
	document.querySelector('.modal_bg').style.display = 'none';
}