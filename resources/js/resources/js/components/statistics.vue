<template>
    <div>
        <h1>Statistics</h1>

        <div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Total Users</th>
                        <th>Total Administrators</th>
                        <th>Total Operators</th>
                        <th>Total Platform Users</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{totalUsers}}</td>
                        <td>{{totalAdmins}}</td>
                        <td>{{totalOperators}}</td>
                        <td>{{totalPlatformUsers}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    data: function () {
			return {
                totalUsers: "",
                totalAdmins: "",
                totalOperators: "",
                totalPlatformUsers: "",
			}
		},
        methods: {
            getTotalUsers(){
                axios.get("api/totalUsers").then(({ data }) => (this.totalUsers = data));
            },
            getTotalAdmins(){
                axios.get("api/totalAdmins").then(({ data }) => (this.totalAdmins = data));
            },
            getTotalOperators(){
                axios.get("api/totalOperators").then(({ data }) => (this.totalOperators = data));
            },
            getTotalPlatformUsers(){
                axios.get("api/totalPlatformUsers").then(({ data }) => (this.totalPlatformUsers = data));
            },
            
        },
        created(){
            this.getTotalUsers();
            this.getTotalAdmins();
            this.getTotalOperators();
            this.getTotalPlatformUsers();  
        }
    }

</script>