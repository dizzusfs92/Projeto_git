/**
 * Passa os dados do cliente para o Modal, e atualiza o link para exclusão
 */
$('#delete-modal').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('categoria');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Plano de Conta #' + id);
  modal.find('#confirm').attr('href', 'delete.php?id=' + id);
})

$('#delete-modal-SubCategoria').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('subcategoria');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Subgrupo #' + id);
  console.log('ABRIU');
  modal.find('#confirm').attr('href', 'delete.php?subcat_id=' + id);
})

$('#delete-modal-CentroCusto').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('centrocusto');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Centro de Custo #' + id);
  modal.find('#confirm').attr('href', 'delete.php?id=' + id);
})

$('#delete-modal-SubCentroCusto').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('subcentrocusto');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Subgrupo #' + id);
  modal.find('#confirm').attr('href', 'delete.php?subcat_id=' + id);
})

$('#delete-modal-ContaCorrente').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('contacorrente');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Conta Corrente #' + id);
  modal.find('#confirm').attr('href', 'delete.php?id=' + id);
})

$('#delete-modal-Movimentacao').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('movimentacao');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Movimentação #' + id);
  modal.find('#confirm').attr('href', 'delete.php?id=' + id + '&id_conta=' + document.getElementById('DropDownContas').value);
})

$('#delete-modal-ContaPagarReceber').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('movimentacao');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Conta a pagar/receber #' + id);
  modal.find('#confirm').attr('href', 'delete.php?id=' + id);
})

$('#delete-modal-Transferencia').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('transferencia').id;
  var id_vinculado = button.data('transferencia').id_vinculado;
  console.log('TESTANDO:',id_vinculado);
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Transferência');
  modal.find('#confirm').attr('href', 'deleteTransferencia.php?id=' + id + '&id_vinculado=' + id_vinculado);
})

$('#delete-modal-Consulta-Transferencia').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('transferencia').id;
  var id_vinculado = button.data('transferencia').id_vinculado;
  console.log('TESTANDO:',id_vinculado);
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Transferência');
  modal.find('#confirm').attr('href', 'deleteTransferencia.php?id=' + id + '&id_vinculado=' + id_vinculado);
})

$('#delete-modal-Pessoa').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('pessoa');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Pessoa #' + id);
  modal.find('#confirm').attr('href', 'delete.php?id=' + id);
  console.log(id);
})

$('#delete-modal-Filial').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('filial');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Filial #' + id);
  modal.find('#confirm').attr('href', 'delete.php?id=' + id);
})

$('#delete-modal-Consulta').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  var id = button.data('consulta');
  
  var modal = $(this);
  modal.find('.modal-title').text('Excluir Movimentação #' + id);
  modal.find('#confirm').attr('href', 'delete.php?id=' + id);
})

$('#assineja-modal-Filial').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  //var id = button.data('pessoa');
  
  var modal = $(this);
  modal.find('.modal-title').text('Filiais');
  modal.find('#confirm').attr('href', '../assineJa.php');
})

$('#assineja-modal-ContaCorrente').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  //var id = button.data('pessoa');
  
  var modal = $(this);
  modal.find('.modal-title').text('ContasCorrentes');
  modal.find('#confirm').attr('href', '../assineJa.php');
})

$('#assineja-modal-DRE').on('show.bs.modal', function (event) {
  
  var button = $(event.relatedTarget);
  //var id = button.data('pessoa');
  
  var modal = $(this);
  modal.find('.modal-title').text('DRE');
  modal.find('#confirm').attr('href', '../assineJa.php');
})

