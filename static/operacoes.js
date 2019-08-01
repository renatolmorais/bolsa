$(document).ready(function () {
        $('#jt_operacoes').jtable({
            title: 'Investimentos',
			paging: true,
			pageSize: 20,
			cache: false,
            actions: {
                listAction: '/api/list.php',
				createAction: '/api/create.php',
				updateAction: '/api/update.php',
				deleteAction: '/api/delete.php'
            },
            fields: {
                id: {
                    key: true,
                    list: false
                },
                data: {
                    title: 'Data',
					type: 'date',
					width: '15%'
                },
                operacao: {
                    title: 'Operação',
					options: [{Value:"1",DisplayText:"COMPRA"},{Value:"2",DisplayText:"VENDA"}],
					width: '15%'
                },
                codigo: {
                    title: 'Código',
					width: '12.5%'
                },
				quantidade: {
					title: 'Quantidade'
				},
				preco: {
					title: 'Preço'
				},
				valor: {
					title: 'Valor',
					width: '5%'
				},
				emolumentos: {
					title: "Emolumentos",
					create: false,
					edit: false,
					width: '5%'
				},
				liquidacao: {
					title: "Liquidação",
					create: false,
					edit: false,
					width: '5%'
				}
			}
		});
		$("#jt_operacoes").jtable("load");
    });