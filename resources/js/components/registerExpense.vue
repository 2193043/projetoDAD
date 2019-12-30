<template>
  <div>
    <h2>Register Expense</h2>
    <div class="alert" :class="typeofmsg" v-if="showMessage">
      <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
      <strong>{{ message }}</strong>
    </div>
    <div>
        <div class="form-group">
            <label for="inputValue">Value</label>
            <input type="text" id="inputValue" class="form-control" v-model="value"/>
        </div>

        <div class="form-group">
            <label for="inputDescription">Description</label>
            <textarea class="form-control" v-model="description"></textarea>
          </div>

        <div class="form-group" >
            <label for="inputType">Category</label>
            <select class="form-control" v-model="typeCategory">
              <option v-for="category in categories"  :key="category.id" :value="category.id">{{category.name}}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="inputType">Type Movement</label>
            <select class="form-control" v-model="typeMovement">
              <option value="e">External</option>
              <option value="t">Transfer</option>
            </select>
        </div>

          

          <!-- External Payment -->
          <div v-if="typeMovement === 'e'">
            <div class="form-group">
                <label for="inputType">Type Payment</label>
                <select class="form-control" v-model="typePayment">
                    <option value="mb">MB Payment</option>
                    <option value="bt">Bank Transfer</option>
                </select>
            </div>
            <!-- External Payment - Type MB-->
            <div v-if="typePayment === 'mb'">
                <div class="form-group">
                    <label for="inputCode">Code</label>
                    <input id="inputCode" class="form-control" v-model="mbCode"/>
                </div>
                <div class="form-group">
                    <label for="inputRef">Reference</label>
                    <input id="inputRef" class="form-control" v-model="mbReference"/>
                </div>
            </div>
            <!-- External Payment - Type BT-->
            <div v-if="typePayment === 'bt'">
                <div class="form-group">
                    <label for="inputIBAN">IBAN</label>
                    <input id="inputIBAN" class="form-control" v-model="IBAN"/>
                </div>
            </div>
          </div>
      
            <!-- Transfer Payment -->
            <div v-if="typeMovement === 't'">
                <div class="form-group">
                    <label for="inputEmail">Wallet Email</label>
                    <input type="email" name="email" id="inputEmail" class="form-control" v-model="email" />
                </div>
                <div class="form-group" v-show="!walletExist">
                    <a class="btn btn-primary" v-on:click.prevent="getWallet(email)">Verify Wallet</a>
                </div>
            </div>

          

          <div class="form-group" v-if="typeMovement === 'e' && typePayment === 'mb'">
              <div v-for="currentWallet in myWallet" :key="currentWallet.id">
                  <a class="btn btn-primary" v-on:click.prevent="externalPaymentTypeMB(value, description, typeCategory,typePayment,mbCode, mbReference, currentWallet.balance)">Register</a>
              </div>
            
          </div>
          <div class="form-group" v-if="typeMovement === 'e' && typePayment === 'bt'">
            <div v-for="currentWallet in myWallet" :key="currentWallet.id">
                  <a class="btn btn-primary" v-on:click.prevent="externalPaymentTypeBT(value, description, typeCategory,typePayment, IBAN, currentWallet.balance)">Register</a>
              </div>
          </div>
          <div class="form-group" v-if="typeMovement === 't'">
              <div v-show="walletExist">
                  <div v-for="currentWallet in myWallet" :key="currentWallet.id">
                    <div v-for="wallet in wallets" :key="wallet.id">
                        <a class="btn btn-primary" v-on:click.prevent="transferPayment(wallet.id,value, description, typeCategory, currentWallet.balance, wallet.balance)">Register</a>
                    </div>
                  </div>
                  
              </div>
          </div>
    </div>
  </div>
</template>
<script>
   export default {
	   
		data: function () {
			return {
                categories: {},
                myWallet: {},
                wallets: {}, 
                typeofmsg: "",
                showMessage: false,
                message: "",
                value: "",
                typeCategory: "",
                typeMovement: "",
                description: "",
                typePayment: "",
                mbCode: "",
                mbReference: "",
                IBAN: "",
                email: "",
                walletExist: false,
			}
		},
		methods: {
			loadCategories(){
				axios.get('api/getexpensecategory').then(({data})=>(this.categories=data));
            },
            loadMyCurrentBalance(){
				axios.get('api/getcurrentbalance/'+this.$store.state.user.id).then(({data})=>(this.myWallet=data));
            },
            cleanValues(){
                this.typeMovement = "";
                this.value = "";
                this.typeCategory= "";
                this.description = "";
                this.typePayment= "";
                this.mbCode= "";
                this.mbReference= "";
                this.IBAN= "";
                this.email= "";
                this.walletExist= false;
            },
            getWallet(email) {
                axios.get("api/verifyemailwallet/" + email).then(response => {
                    if (response.data == 1) {
                        this.showMessage = true;
                        this.typeofmsg = "alert-success";
                        this.message = "Wallet Exist";
                        axios.get("api/getwallet/" + email).then(({ data }) => (this.wallets = data));
                        this.walletExist = true;
                    } else {
                        this.showMessage = true;
                        this.typeofmsg = "alert-danger";
                        this.message = "Wallet don't exist";
                    }
                });
            },
            externalPaymentTypeMB(value, description, typeCategory,typePayment,mbCode, mbReference, balance){
                if (parseInt(value) < 0.01 || parseInt(value) > 5000.0) {
                    this.typeofmsg = "alert-danger";
                    this.message = "Value must be between (0.00) and (5000.00)";
                    this.showMessage = true;
                } else {
                    var totalBalance = parseInt(balance) - parseInt(value);
                    let today = new Date(new Date().toString().split("GMT")[0] + " UTC").toISOString().split(".")[0].replace("T", " ");
                    axios.post("/api/externalpaymentmb", {
                        wallet_id: this.$store.state.user.id,
                        type: "e",
                        transfer: "0",
                        type_payment: typePayment,
                        category_id: typeCategory,
                        mb_entity_code: mbCode,
                        mb_payment_reference: mbReference,
                        description: description,
                        date: today,
                        start_balance: balance,
                        value: value,
                        end_balance: totalBalance
                    })
                    .then(response => {
                        this.typeofmsg = "alert-success";
                        this.message = "Movement inserted with success";
                        this.showMessage = true;
                        axios.put("api/wallets/" + this.$store.state.user.id, {
                            balance: totalBalance
                        })
                        .then(response => {
                            this.cleanValues();
                        });
                    })
                    .catch(error => {
                        this.typeofmsg = "alert-danger";
                        this.message = "Error inserting movement";
                        this.showMessage = true;
                    });
                }
            },
            externalPaymentTypeBT(value, description, typeCategory,typePayment,IBAN, balance){
                if (parseInt(value) < 0.01 || parseInt(value) > 5000.0) {
                    this.typeofmsg = "alert-danger";
                    this.message = "Value must be between (0.00) and (5000.00)";
                    this.showMessage = true;
                } else {
                    var totalBalance = parseInt(balance) - parseInt(value);
                    let today = new Date(new Date().toString().split("GMT")[0] + " UTC").toISOString().split(".")[0].replace("T", " ");
                    axios.post("/api/externalpaymentbt", {
                        wallet_id: this.$store.state.user.id,
                        type: "e",
                        transfer: "0",
                        type_payment: typePayment,
                        category_id: typeCategory,
                        iban: IBAN,
                        description: description,
                        date: today,
                        start_balance: balance,
                        value: value,
                        end_balance: totalBalance
                    })
                    .then(response => {
                        this.typeofmsg = "alert-success";
                        this.message = "Movement inserted with success";
                        this.showMessage = true;
                        axios.put("api/wallets/" + this.$store.state.user.id, {
                            balance: totalBalance
                        })
                        .then(response => {
                            this.cleanValues();
                        });
                    })
                    .catch(error => {
                        this.typeofmsg = "alert-danger";
                        this.message = "Error inserting movement";
                        this.showMessage = true;
                    });
                }
            },
            transferPayment(id,value, description, typeCategory,expenseBalance, incomeBalance){
                if (parseInt(value) < 0.01 || parseInt(value) > 5000.0) {
                    this.typeofmsg = "alert-danger";
                    this.message = "Value must be between (0.00) and (5000.00)";
                    this.showMessage = true;
                } else {
                    var idAuxIncome = "";
                    var idAuxExpense = "";
                    var totalBalanceExpense = parseInt(expenseBalance) - parseInt(value);
                    var totalBalanceIncome = parseInt(incomeBalance) + parseInt(value);
                    let today = new Date(new Date().toString().split("GMT")[0] + " UTC").toISOString().split(".")[0].replace("T", " ");
                    axios.post("/api/transferpayment", {
                        wallet_id: this.$store.state.user.id,
                        type: "e",
                        transfer: "1",
                        //transfer_movement_id: id + 1,
                        transfer_wallet_id: id,
                        category_id: typeCategory,
                        description: description,
                        date: today,
                        start_balance: expenseBalance,
                        value: value,
                        end_balance: totalBalanceExpense
                    })
                    .then(response => {
                        idAuxExpense = response.data.data.id;
                        axios.post("/api/incomemovementtransfer", {
                            wallet_id: id,
                            type: "i",
                            transfer: "1",
                            transfer_movement_id: response.data.data.id,
                            transfer_wallet_id: this.$store.state.user.id,
                            source_description: description,
                            date: today,
                            start_balance: incomeBalance,
                            value: value,
                            end_balance: totalBalanceIncome
                        })
                        .then(response =>{
                            idAuxIncome = response.data.data.id;
                            console.log(idAuxExpense)
                            console.log(idAuxIncome);
                            axios.put("api/wallets/" + id, {
                                balance: totalBalanceIncome
                            })
                            .then(response =>{
                                axios.put("api/wallets/" + this.$store.state.user.id, {
                                    balance: totalBalanceExpense
                                })
                                .then(response =>{
                                    axios.put("api/movements/" + idAuxExpense, {
                                        transfer_movement_id: idAuxIncome
                                     }).then();
                                    this.typeofmsg = "alert-success";
                                    this.message = "Movement inserted with success";
                                    this.showMessage = true;
                                    this.cleanValues();
                                });
                                
                            });
                        });     
                    })
                    .catch(error => {
                        this.typeofmsg = "alert-danger";
                        this.message = "Error inserting movement";
                        this.showMessage = true;
                    });
                }
            }
			
		},
		created() {
            this.loadCategories();
            this.loadMyCurrentBalance();
		}
	}
</script>