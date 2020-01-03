<template>
  <div>
    <h2>Register User</h2>
    <div class="alert" :class="typeofmsg" v-if="showMessage">
      <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
      <strong>{{ message }}</strong>
    </div>
    <div>
      <div class="form-group">
        <label for="inputName">Name<i style="color: red;">*</i></label>
        <input type="text" name="name" id="inputName" class="form-control" v-model="name" />
      </div>
      <div class="form-group">
        <label for="inputEmail">Email<i style="color: red;">*</i></label>
        <input type="email" name="email" id="inputEmail" class="form-control" v-model="email" />
        <a class="btn btn-primary" v-on:click.prevent="verifyEmail(email)">Verify Email</a>
      </div>
      <div class="form-group">
        <label for="inputNIF">NIF<i style="color: red;">*</i></label>
        <input type="text" name="nif" id="inputNIF" class="form-control" v-model="NIF" />
      </div>
      <div class="form-group">
        <label for="inputPassword1">Password<i style="color: red;">*</i></label>
        <input v-model="password" type="password" name="password1" id="inputPassword1" class="form-control"/>
      </div>
      <div class="form-group">
        <label for="inputPassword2">Confirm Password<i style="color: red;">*</i></label>
        <input v-model="password_confirmation" type="password" name="password2" id="inputPassword2" class="form-control"/>
      </div>
      <div class="form-group">
        <a class="btn btn-primary" v-on:click.prevent="registerUser(name, email, NIF, password, password_confirmation)">Register</a>
        <a class="btn btn-light" v-on:click.prevent="cancelSaveUser()">Cancel</a>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
export default {
  data: function() {
    return {
      name: "",
      email: "",
      NIF: "",
      password: "",
      password_confirmation: "",
      typeofmsg: "",
      showMessage: false,
      message: "",
    };
  },
  methods: {
    registerUser(name, email, NIF, password, password_confirmation) {

      if (password === password_confirmation) {
        axios.post("/api/users", {
            name: name,
            email: email,
            nif: NIF,
            password: password,
          })
          .then(response => {
            this.showMessage = true;
            this.typeofmsg = "alert-success";
            this.message = "User ceated with success";
            axios.post("/api/wallets", {
                id: response.data.data.id,
                email: email,
                balance: "0"
              }).then();
          })
          .catch(error => {
            this.showMessage = true;
            this.typeofmsg = "alert-danger";
            this.message = "Error creating user";
          });
      } else {
        this.showMessage = true;
        this.typeofmsg = "alert-danger";
        this.message = "Password and password confirmation must be the same";
      }
    },
    cancelSaveUser() {
      this.$router.push("/");
    },
    verifyEmail(email){
      axios.get("api/verifyemail/"+email)
      .then(response => {
        if(response.data == 0){
          this.showMessage = true;
          this.typeofmsg = "alert-success";
          this.message = "Email available";
        }else{
          this.showMessage = true;
          this.typeofmsg = "alert-danger";
          this.message = "Email not available";
        }
      });
      
    }
  }
};
</script>