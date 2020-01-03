<template>
  <div>
    <h2>Change Password</h2>
    <div class="alert" :class="typeofmsg" v-if="showMessage">             
            <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
            <strong>{{ message }}</strong>
        </div>
    <div>
       <div class="form-group">
            <label for="inputOldPassword">Current Password</label>
            <input v-model="password_old" type="password" name="oldpassword" id="inputOldPassword" class="form-control">
        </div>
        <div class="form-group">
            <label for="inputNewPassword1">New Password</label>
            <input  v-model="new_password" type="password" name="newpassword1" id="inputNewPassword1" class="form-control">
        </div>
        <div class="form-group">
            <label for="inputNewPassword2">Confirm New Password</label>
            <input v-model="password_confirmation" type="password" name="newpassword2" id="inputNewPassword2" class="form-control">
        </div>       
      <div class="form-group" >
        <a class="btn btn-primary" v-on:click.prevent="changePassword(password_old, new_password, password_confirmation)">Change Password</a>
		<a class="btn btn-light" v-on:click.prevent="cancelSaveUser()">Cancel</a>
	  </div>
    </div>
  </div>
</template>

<script type="text/javascript">
export default {
		data: function() {
			return {
                password_old:'',
                new_password:'',
                password_confirmation:'',
				typeofmsg: "",
        		showMessage: false,
			 	message: ""
			};
		},
		methods:{
			changePassword(password_old, new_password, password_confirmation){
				let token = sessionStorage.getItem('token');
				if(password_old === new_password){
					this.showMessage = true;
					this.typeofmsg = "alert-danger";
					this.message = "New password must be diferent from current password";
				}else{
					if(new_password === password_confirmation){
					
						axios.put('/api/users/password/'+this.$store.state.user.id,
						{
							password: new_password,
						},
						{
							headers: {
								Authorization: "Bearer " + token
							},
						})
						.then(response => {
							this.showMessage = true;
							this.typeofmsg = "alert-success";
							this.message = "Password change with success";
						})
						.catch(error => {
							this.showMessage = true;
							this.typeofmsg = "alert-danger";
							this.message = "Error change password";
						})
					}else{
						this.showMessage = true;
						this.typeofmsg = "alert-danger";
						this.message = "New password and password confirmation must be the same";
					}
				}
				
				
			},
            cancelSaveUser(){
                this.$router.push('/');
            }
		}		
	};
</script>