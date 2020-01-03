<template>
  <div>
    <h2>Register Administrator or Operator</h2>
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
            <label for="inputType">Type User<i style="color: red;">*</i></label>
            <select class="form-control" v-model="type">
              <option value="o">Operator</option>
              <option value="a">Administrator</option>
            </select>
        </div>
        <div class="form-group">
        <label for="inputPassword1">Password<i style="color: red;">*</i></label>
        <input
          v-model="password" type="password" name="password1" id="inputPassword1" class="form-control"/>
      </div>
      <div class="form-group">
        <label for="inputPassword2">Confirm Password<i style="color: red;">*</i></label>
        <input v-model="password_confirmation" type="password" name="password2" id="inputPassword2" class="form-control"/>
      </div>
      <div class="form-group" v-if="type === 'o'">
        <a
          class="btn btn-primary"
          v-on:click.prevent="registerOperator(name, email, password, password_confirmation)"
        >Register</a>
      </div>
      <div class="form-group" v-if="type === 'a'">
        <a
          class="btn btn-primary"
          v-on:click.prevent="registerAdmin(name, email, password, password_confirmation)"
        >Register</a>
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
      type: "",
      password: "",
      password_confirmation: "",
      typeofmsg: "",
      showMessage: false,
      message: "", 
    };
  },
  methods: {
    registerAdmin(name, email, password, password_confirmation) {
      if (password === password_confirmation) {
        axios
          .post("/api/registeradmin", {
            name: name,
            email: email,
            type: "a",
            password: password
          })
          .then(response => {
            this.showMessage = true;
            this.typeofmsg = "alert-success";
            this.message = "User ceated with success";
            this.cleanValues();
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
    registerOperator(name, email, password, password_confirmation) {
      if (password === password_confirmation) {
        axios
          .post("/api/registeroperator", {
            name: name,
            email: email,
            type: "o",
            password: password
          })
          .then(response => {
            this.showMessage = true;
            this.typeofmsg = "alert-success";
            this.message = "User ceated with success";
            this.cleanValues();
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
      
    },
    cleanValues(){
        this.email = "";
        this.type = "";
        this.name= "";
        this.password = "";
        this.password_confirmation= "";
    }
  }
};
</script>