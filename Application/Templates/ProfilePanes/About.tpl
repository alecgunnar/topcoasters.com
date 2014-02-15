{% import "Macros/DataTable.tpl" as dataTable %}

{{ dataTable.build("About " ~ showMember.get('username'), data)|raw }}