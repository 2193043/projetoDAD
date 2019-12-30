<template>
  <div>
    <h2>Register Income Movement</h2>
    <div class="alert" :class="typeofmsg" v-if="showMessage">
      <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
      <strong>{{ message }}</strong>
    </div>
    <div>
      <div v-show="!walletExist">
        <div class="form-group">
          <label for="inputEmail">Wallet Email</label>
          <input type="email" name="email" id="inputEmail" class="form-control" v-model="email" />
        </div>
        <div class="form-group">
          <a class="btn btn-primary" v-on:click.prevent="getWallet(email)">Verify Wallet</a>
        </div>
      </div>

      <div v-show="walletExist">
        <div v-for="wallet in wallets" :key="wallet.id">
          <div class="form-group">
            <label for="inputValue">Value</label>
            <input type="text" name="nif" id="inputValue" class="form-control" v-model="value" />
          </div>
          <div class="form-group">
            <label for="inputType">Type Payment</label>
            <select class="form-control" v-model="typePayment">
              <option value="c">Cash</option>
              <option value="bt">Bank Transfer</option>
            </select>
          </div>
          <div class="form-group" v-if="typePayment === 'bt'">
            <label for="inputIBAN">IBAN</label>
            <input v-model="IBAN" name="iban" id="inputIBAN" class="form-control" />
          </div>

          <div class="form-group">
            <label for="inputDescription">Description</label>
            <textarea class="form-control" v-model="description"></textarea>
          </div>

          <div class="form-group">
            <a
              class="btn btn-primary"
              v-on:click.prevent="registerIncomeMovement(wallet.id, email,value, typePayment, IBAN, description, wallet.balance)"
            >Register</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
export default {
  data: function() {
    return {
      wallets: {},
      email: "",
      value: "",
      typePayment: "",
      IBAN: null,
      description: null,
      typeofmsg: "",
      showMessage: false,
      message: "",
      walletExist: false
    };
  },
  methods: {
    getWallet(email) {
      axios.get("api/verifyemailwallet/" + email).then(response => {
        if (response.data == 1) {
          this.showMessage = true;
          this.typeofmsg = "alert-success";
          this.message = "Wallet Exist";
          axios
            .get("api/getwallet/" + email)
            .then(({ data }) => (this.wallets = data));
          this.walletExist = true;
        } else {
          this.showMessage = true;
          this.typeofmsg = "alert-danger";
          this.message = "Wallet don't exist";
        }
      });
    },
    registerIncomeMovement(
      id,
      email,
      value,
      typePayment,
      IBAN,
      description,
      balance
    ) {
      if (parseInt(value) < 0.01 || parseInt(value) > 5000.0) {
        //console.log(value);
        this.typeofmsg = "alert-danger";
        this.message = "Value must be between (0.00) and (5000.00)";
        this.showMessage = true;
      } else {
        //console.log(id)
        //console.log(balance)
        //console.log(value)
        var totalBalance = parseInt(value) + parseInt(balance);
        //console.log(totalBalance);
        let today = new Date(new Date().toString().split("GMT")[0] + " UTC")
          .toISOString()
          .split(".")[0]
          .replace("T", " ");
        axios
          .post("/api/incomemovement", {
            wallet_id: id,
            type: "i",
            transfer: "0",
            type_payment: typePayment,
            iban: IBAN,
            source_description: description,
            date: today,
            start_balance: balance,
            value: value,
            end_balance: totalBalance
          })
          .then(response => {
            this.typeofmsg = "alert-success";
            this.message = "Movement inserted with success";
            this.showMessage = true;
            axios
              .put("api/wallets/" + id, {
                balance: totalBalance
              })
              .then(response => {
                this.walletExist = false;
                this.email= "";
              });
          })
          .catch(error => {
            this.typeofmsg = "alert-danger";
            this.message = "Error inserting movement";
            this.showMessage = true;
          });
      }
    }
  }
};
</script>