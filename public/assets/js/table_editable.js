$(function () {

    "use strict";

    function editableTable() {

        function restoreRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);

            for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                oTable.fnUpdate(aData[i], nRow, i, false);
            }
            oTable.fnDraw();
        }

        function editRow(oTable, nRow) {
            var aData = oTable.fnGetData(nRow);
            var jqTds = $('>td', nRow);
            jqTds[0].innerHTML = '<input type="text" class="form-control small" value="' + aData[0] + '">';
            jqTds[1].innerHTML = '<input type="text" class="form-control small" value="' + aData[1] + '">';
            jqTds[2].innerHTML = '<input type="text" class="form-control small" value="' + aData[2] + '">';
            jqTds[3].innerHTML = '<input type="text" class="form-control small" value="' + aData[3] + '">';
            jqTds[4].innerHTML = '<div class="text-center"><button type="button" class="edit btn btn-sm btn-success btn-bordered btn-flat" href=""><i class="fa fa-save"></i></button> <a class="delete btn btn-sm btn-danger btn-bordered btn-flat" href=""><i class="fa fa-trash"></i></a></div>';
        }

        function saveRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
            oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
            oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
            oTable.fnUpdate('<div class="text-center"><a class="edit btn btn-sm btn-primary btn-bordered btn-flat" href=""><i class="fa fa-pencil-square-o"></i></a> <a class="delete btn btn-sm btn-danger btn-bordered btn-flat" href=""><i class="fa fa-trash"></i></a></div>', nRow, 4, false);
            oTable.fnDraw();
        }

        function cancelEditRow(oTable, nRow) {
            var jqInputs = $('input', nRow);
            oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
            oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
            oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
            oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
            oTable.fnUpdate('<a class="edit btn btn-sm btn-primary btn-bordered btn-flat" href=""><i class="fa fa-pencil-square-o"></i></a>', nRow, 4, false);
            oTable.fnDraw();
        }

        var oTable = $('#table-editable').dataTable();

        jQuery('#table-edit_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
        jQuery('#table-edit_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown

        var nEditing = null;

        $('#table-edit_new').click(function (e) {
            e.preventDefault();
            var aiNew = oTable.fnAddData(['', '', '', '',
                    '<p class="text-center"><a class="edit btn btn-sm btn-primary btn-bordered btn-flat" href=""><i class="fa fa-pencil-square-o"></i>Edit</a> <a class="delete btn btn-sm btn-danger btn-bordered btn-flat" href=""><i class="fa fa-trash"></i> Remove</a></p>'
            ]);
            var nRow = oTable.fnGetNodes(aiNew[0]);
            editRow(oTable, nRow);
            nEditing = nRow;
        });

        $('#table-editable a.delete').on('click', function (e) {
            e.preventDefault();
            if (confirm("Are you sure to delete this row ?") == false) {
                return;
            }
            var nRow = $(this).parents('tr')[0];
            oTable.fnDeleteRow(nRow);
            // alert("Deleted! Do not forget to do some ajax to sync with backend :)");
        });

        $('#table-editable a.cancel').on('click', function (e) {
            e.preventDefault();
            if ($(this).attr("data-mode") == "new") {
                var nRow = $(this).parents('tr')[0];
                oTable.fnDeleteRow(nRow);
            } else {
                restoreRow(oTable, nEditing);
                nEditing = null;
            }
        });

        $(document).on('click', '#table-editable .edit', function (e) {
            e.preventDefault();
            /* Get the row as a parent of the link that was clicked on */
            var nRow = $(this).parents('tr')[0];

            if (nEditing !== null && nEditing != nRow) {
                restoreRow(oTable, nEditing);
                editRow(oTable, nRow);
                nEditing = nRow;
            } else if (nEditing == nRow && this.innerHTML == "Save") {
                 /* This row is being edited and should be saved */
                saveRow(oTable, nEditing);
                nEditing = null;
                // alert("Updated! Do not forget to do some ajax to sync with backend :)");
            } else {
                 /* No row currently being edited */
                editRow(oTable, nRow);
                nEditing = nRow;
            }
        });

        $('.dataTables_filter input').attr("placeholder", "Cari data...");

    };

    editableTable();

});