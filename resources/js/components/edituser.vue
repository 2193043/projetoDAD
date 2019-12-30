<template>
    <div>
        <div>
    	<h2>Edit User</h2> <br>
		<div class="alert" :class="typeofmsg" v-if="showMessage">             
            <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
            <strong>{{ message }}</strong>
        </div>   	
        <div class="form-group">
        	<label for="inputName">Name</label>
        	<input type="text" name="name" id="inputName" placeholder="Fullname" class="form-control" v-model="newName">
        </div>
        <div class="form-group">
        	<label for="inputEmail">Email</label>
        	<input type="email" name="email" id="inputEmail" placeholder="Email address" class="form-control" v-model="email" readonly>
        </div>
        <div class="form-group">
        	<label for="inputNIF">NIF</label>
        	<input type="nif" name="nif" id="inputNIF" placeholder="NIF" class="form-control" v-model="newNIF">
        </div>
        <br>
        <div class="form-group" v-cloak>
        	<a class="btn btn-primary" v-on:click.prevent="saveUser(newName, newNIF)">Save</a>
        	<a class="btn btn-light" v-on:click.prevent="cancelSaveUser()">Cancel</a>
        </div>
    </div>
</div>         
</template>

<script type="text/javascript">    
    
    export default {
		data: function () {
			return {
                newNIF:this.$store.state.user.nif, 
				newName: this.$store.state.user.name,
				email: this.$store.state.user.email,
				typeofmsg: "",
                showMessage: false,
				message: "",
			}
		},
		methods: {
			saveUser(newName, newNIF){
				axios.put('api/users/'+this.$store.state.user.id, {
                      name: newName,
                      nif: newNIF,
				}).then(response=>{
					this.$store.commit('setUser',response.data.data);
					this.showMessage = true;
					this.typeofmsg = "alert-success";
                    this.message = "User's Profile Updated";
                })
                .catch(error => {
					this.showMessage = true;
					this.typeofmsg = "alert-danger";
					this.message = "Error updating data";
				})
            },
            cancelSaveUser(){
                this.$router.push('/');
            }
			
		}
}
</script>