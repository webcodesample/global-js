function setPrintInvoiceNo() { var e; CustomerShortName = "", InvoiceIssuer = "", InvoiceType = "", InvoiceMonth = document.getElementById("invoice_month").value, InvoiceFY = document.getElementById("invoice_fy").value, "M" == document.getElementById("invoice_type").value && (InvoiceType = "/M"), "S" == document.getElementById("invoice_type").value && (InvoiceMonth = "/" + document.getElementById("invoice_idnew").value), "RN" == document.getElementById("invoice_type").value ? document.getElementById("inv_label").innerHTML = "Reimbursement Note" : document.getElementById("inv_label").innerHTML = "Invoice No.", document.getElementById("invoice_issuer").value && (InvoiceIssuer = "M" == document.getElementById("invoice_type").value || "S" == document.getElementById("invoice_type").value ? "/" + document.getElementById("invoice_issuer").value.substring(0, 2) : "/" + document.getElementById("invoice_issuer").value.substring(0, 3)), document.getElementById("from").value && (e = document.getElementById("from").value.split(" - "), CustomerShortName = e[2]), document.getElementById("invoice_id_print").value = CustomerShortName + InvoiceIssuer + InvoiceType + InvoiceMonth + InvoiceFY }