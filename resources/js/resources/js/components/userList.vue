<template>
  <div>
    <div class="alert" :class="typeofmsg" v-if="showMessage">
      <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
      <strong>{{ message }}</strong>
    </div>
    <div>
      <h1>All Users</h1>
    </div>
    <div>
      <ul class="nav justify-content-center">
        <li class="nav-item">
          <button class="btn btn-secondary" v-on:click.prevent="loadUsers()">All Users</button>
        </li>
        <li class="nav-item">
          <button class="btn btn-secondary" v-on:click.prevent="loadOperators()">Operators</button>
        </li>
        <li class="nav-item">
          <button class="btn btn-secondary" v-on:click.prevent="loadAdmins()">Administrators</button>
        </li>
        <li class="nav-item">
          <button class="btn btn-secondary" v-on:click.prevent="loadPlatformUsers()">Plataform Users</button>
        </li>
        <li class="nav-item">
          <div class="input-group">
            
            <select class="form-control" v-model="typeSearch">
              <option value="name">Name</option>
              <option value="email">Email</option>
            </select>
            <input :placeholder= typeSearch v-model="searchValue">
            <div class="input-group-append" v-if="typeSearch ==='name'">
              <button class="btn btn-secondary" v-on:click.prevent="searchByName(searchValue)">Search</button>
            </div>
            <div class="input-group-append" v-if="typeSearch ==='email'">
              <button class="btn btn-secondary" v-on:click.prevent="searchByEmail(searchValue)">Search</button>
            </div>
        </div>
        </li>
      </ul>
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Photo</th>
          <th>Name</th>
          <th>Email</th>
          <th>Type User</th>
          <th>Status</th>
          <th>Balance</th>
          <th>Actions</th>

        </tr>
      </thead>
      <tbody v-for="user in users" :key="user.id" :user="currentUser">
        <tr>
          <td>
            <div v-if="user.photo != null">
                  <img v-bind:src="'/storage/fotos/'+user.photo" height="60px" width="auto" />
              </div>
          </td>
          <td>{{user.name}}</td>
          <td>{{user.email}}</td>
          <td>
            <div v-if="user.type === 'a'">Administrator</div>
            <div v-if="user.type === 'o'">Operator</div>
            <div v-if="user.type === 'u'">Platform User</div>
          </td>
          <td>
            <div v-if="user.active == 1">Active</div>
            <div v-if="user.active == 0">Inactive</div>
          </td>
          <td>
            <div v-if="user.type === 'u'">
              <div v-if="user.balance == 0">Empty</div>
              <div v-else>Has Money</div>
            </div>
            <div v-else>Not a Platform user</div>
          </td>
          <td>
            <!--Platform users-->
            <div v-if="user.type === 'u'">
              <div v-if="user.balance == 0 && user.active == 1">
                <a class="btn btn-sm btn-danger" v-on:click.prevent="deactivateUser(user.id)">Deactivate</a>
              </div>
              <div v-if="user.active == 0">
                <a class="btn btn-sm btn-success" v-on:click.prevent="reactivateUser(user.id)">Reactivate</a>
              </div>
            </div>
            <!--Other users-->
            <div v-else>
              <div v-if="user.id != currentUser">
                <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user.id, user.type)">Delete</a>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="pagination">
				<button class="btn btn-secondary" v-on:click.prevent="fetchPaginateMovements(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">Previous</button>
				<span>Page {{pagination.current_page}} of {{pagination.last_page}}</span>
				<button class="btn btn-secondary" v-on:click.prevent="fetchPaginateMovements(pagination.next_page_url)" :disabled="!pagination.next_page_url">Next</button>
		</div>
  </div>
</template>
<script>
export default {
  data: function() {
    return {
      currentUser:this.$store.state.user.id,
      users: {},
      typeofmsg: "",
      showMessage: false,
      message: "",
      typeSearch: "name",
      searchValue: "",
      pagination: {},
			url: 'api/users'
    };
  },
  methods: {
    makePagination(data){
				let pagination = {
					current_page: data.current_page,
					last_page: data.last_page,
					next_page_url: data.next_page_url,
					prev_page_url: data.prev_page_url
				}
				this.pagination = pagination;
			},
			fetchPaginateMovements(url){
				this.url = url;
				this.loadUsers();
			},
    loadUsers() {
      let $this = this;
      axios.get(this.url).then(({ data }) => (this.users = data.data,$this.makePagination(data)));
    },
    loadPlatformUsers() {
      let $this = this;
      axios.get("api/allPlatformUsers").then(({ data }) => (this.users = data.data,$this.makePagination(data)));
    },
    loadAdmins() {
      let $this = this;
      axios.get("api/allAdmins").then(({ data }) => (this.users = data.data,$this.makePagination(data)));
    },
    loadOperators() {
      let $this = this;
      axios.get("api/allOperators").then(({ data }) => (this.users = data.data,$this.makePagination(data)));
    },
    deleteUser(id, typeUser){
      axios.delete("api/users/"+id)
      .then(response =>{
        this.typeofmsg = "alert-success";
        this.message = "User deleted with success";
        this.showMessage = true;
        this.editActive = false;
        if(typeUser === 'a'){
          this.loadAdmins();
        }else{
          this.loadOperators();
        }
			  
		  })
		  .catch(error => {
        this.typeofmsg = "alert-danger";
        this.message = "Error deleting administrator";
        this.showMessage = true;
		  });
    },
    deactivateUser(id){
      axios.put("api/deactivateUser/" + id, {
        active: "0"
      })
      .then(response =>{
        this.typeofmsg = "alert-success";
        this.message = "User deactivated with success";
        this.showMessage = true;
        this.editActive = false;
			  this.loadPlataformUsers()
		  })
		  .catch(error => {
        this.typeofmsg = "alert-danger";
        this.message = "Error deactivating user";
        this.showMessage = true;
		  });
    },
    reactivateUser(id){
      axios.put("api/reactivateUser/" + id, {
        active: "1"
      })
      .then(response =>{
        this.typeofmsg = "alert-success";
        this.message = "User reactivated with success";
        this.showMessage = true;
        this.editActive = false;
			  this.loadPlataformUsers()
		  })
		  .catch(error => {
        this.typeofmsg = "alert-danger";
        this.message = "Error reactivating user";
        this.showMessage = true;
		  });
    },
    searchByName(name){
      let $this = this;
      axios.get("api/searchByName/"+name).then(({ data }) => (this.users = data.data,$this.makePagination(data)));
    },
    searchByEmail(email){
      let $this = this;
      axios.get("api/searchByEmail/"+email).then(({ data }) => (this.users = data.data,$this.makePagination(data)));
    }
  },
  created() {
    this.loadUsers();
  }
};
</script>