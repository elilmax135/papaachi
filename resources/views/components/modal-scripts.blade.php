<script>
    function confirmDelete(userId) {
     Swal.fire({
         title: 'Are you sure?',
         text: 'Do you want to delete this user?',
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#28a745',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes, delete it',
         cancelButtonText: 'No, cancel'
     }).then((result) => {
         if (result.isConfirmed) {
             // If the user clicks "Yes, delete it," submit the form
             document.forms['deleteForm_' + userId].submit();
         }
     });
 }
 </script>
 <script>
  $(document).ready(function () {
     $('.show-user-details').on('click', function (e) {
         e.preventDefault();

         var userId = $(this).data('user-id');

         $.ajax({
             url: "{{ route('users.show', ':id') }}".replace(':id', userId),
             type: 'GET',
             success: function (data) {
                 $('#userDetailsModalBody').html(data);
                 $('#userDetailsModal').modal('show');
             },
             error: function (error) {
                 console.log(error);
             }
         });
     });
 });

 </script>
 <script>
  $(document).ready(function () {
     $('.show-role-details').on('click', function (e) {
         e.preventDefault();

         var roleId = $(this).data('role-id');

         $.ajax({
             url: "{{ route('roles.show', ':id') }}".replace(':id', roleId),
             type: 'GET',
             success: function (data) {
                 $('#roleDetailsModalBody').html(data);
                 $('#roleDetailsModal').modal('show');
             },
             error: function (error) {
                 console.log(error);
             }
         });
     });
 });

 </script>
 <script>
    function confirmDeleteRole(roleId) {
     Swal.fire({
         title: 'Are you sure?',
         text: 'Do you want to delete this role?',
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#28a745',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes, delete it',
         cancelButtonText: 'No, cancel'
     }).then((result) => {
         if (result.isConfirmed) {
             // If the user clicks "Yes, delete it," submit the form
             document.forms['deleteForm_' + roleId].submit();
         }
     });
 }
 </script>
 <script>
    function confirmDeleteSite(siteId) {
     Swal.fire({
         title: 'Are you sure?',
         text: 'Do you want to delete this site?',
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#28a745',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Yes, delete it',
         cancelButtonText: 'No, cancel'
     }).then((result) => {
         if (result.isConfirmed) {
             // If the user clicks "Yes, delete it," submit the form
             document.forms['deleteForm_' + siteId].submit();
         }
     });
 }
 </script>
