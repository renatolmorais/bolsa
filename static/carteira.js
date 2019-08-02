$(document).ready(function () {
        $('#jt_carteira').jtable({
            title: 'Investimentos',
			paging: true,
			pageSize: 20,
			cache: false,
            actions: {
                listAction: '/api/carteira/list.php'/*,
				createAction: '/api/carteira/create.php',
				updateAction: '/api/carteira/update.php',
				deleteAction: '/api/carteira/delete.php'*/
            },
            fields: {
                id: {
                    key: true,
                    list: false
                },
                codigo: {
                    title: 'Ativo',
                },
                quantidade: {
                    title: 'Quantidade',
                }
			}
		});
		$("#jt_carteira").jtable("load");
    });