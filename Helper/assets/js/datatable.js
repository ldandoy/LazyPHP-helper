$(document).ready(function() {
    $("#datatable").DataTable(
        {
            language: {
                "sProcessing":     "Traitement en cours...",
                "sSearch":         "Rechercher&nbsp;:",
                "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
                "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
                "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                "sInfoPostFix":    "",
                "sLoadingRecords": "Chargement en cours...",
                "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
                "oPaginate": {
                    "sFirst":      "Premier",
                    "sPrevious":   "Pr&eacute;c&eacute;dent",
                    "sNext":       "Suivant",
                    "sLast":       "Dernier"
                },
                "oAria": {
                    "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                    "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                }
            }
        }
    );

    $('#ajaxdatatable').DataTable( {
        "ajax": "/cockpit/slots/listing",
        "columns": [
            { "data": "id" },
            { "data": "label" },
            { "data": "sponsorship" },
            { "data": "price" },
            { "data": "quantity" },
            { "data": "fitnss" },
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
        ],
        "columnDefs": [ {
            "targets": -1,
            "data": null,
            render: function ( data, type, columns, meta ) {
                if(type === 'display'){
                    data = '<a href="/cockpit/slots/duplicate/' + data.id + '" class="btn btn-sm btn-info"><i class="fa fa-files-o"></i></a>' +
                    '<a href="/cockpit/slots/show/' + data.id + '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>' +
                    '<a href="/cockpit/slots/edit/' + data.id + '" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>' +
                    '<a href="/cockpit/slots/delete/' + data.id + '" class="btn btn-sm btn-danger" confirmation="Vous confirmer vouloir supprimer cette slot ?"><i class="fa fa-trash-o"></i></a>';
                }
 
                return data;
            },
        } ],
        language: {
            "sProcessing":     "Traitement en cours...",
            "sSearch":         "Rechercher&nbsp;:",
            "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix":    "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst":      "Premier",
                "sPrevious":   "Pr&eacute;c&eacute;dent",
                "sNext":       "Suivant",
                "sLast":       "Dernier"
            },
            "oAria": {
                "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        }
    } );
});