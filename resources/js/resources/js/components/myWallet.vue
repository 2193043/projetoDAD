<template>
  <div>
    <div>
      <h1>My Wallet</h1>
    </div>
    <div class="alert" :class="typeofmsg" v-if="showMessage">
      <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
      <strong>{{ message }}</strong>
    </div>
    <!--Email and Current Balance-->
    <div class="myWallet">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Wallet Email</th>
            <th>Current Balance</th>
          </tr>
        </thead>
        <tbody v-for="wallet in wallets" :key="wallet.id">
          <tr>
            <td>{{wallet.email}}</td>
            <td>{{wallet.balance}}€</td>
          </tr>
        </tbody>
      </table>
    </div>
    <br />
    <div>
      <h4>My Wallet Movements:</h4>
    </div>
    <!--Button to show filters-->
    <div v-show="!showFilter">
      <button class="btn btn-light" v-on:click.prevent="filterData()">Filter Data</button>
    </div>
    <!--Div with options to filter table-->
    <div class="filters" v-show="showFilter">
      <div>
        <label>Select Category</label>
        <select v-model="searchCategoryType">
          <option
            v-for="searchCategory in allCategories"
            :key="searchCategory.id"
            :value="searchCategory.id"
          >{{searchCategory.name}}</option>
        </select>
      </div>

      <div>
        <label>Type Movement</label>
        <select v-model="searchTypeMovement">
          <option value="e">Expense</option>
          <option value="i">Income</option>
        </select>
      </div>

      <div>
        <label>Select Payment</label>
        <select v-model="searchTypePayment">
          <option value="bt">Bank Transfer</option>
          <option value="mb">MB Payment</option>
          <option value="c">Cash</option>
        </select>
      </div>

      <div>
        <label>Transfer Email</label>
        <input type="email" v-model="searchByEmailTransfer" />
      </div>

      <div class="date">
        <label>Date From</label>
		<input v-model="fromYear"/><label>/</label><input v-model="fromMonth"/><label>/</label><input v-model="fromDay"/>
        <label>To</label>
        <input v-model="toYear"/><label>/</label><input v-model="toMonth"/><label>/</label><input v-model="toDay"/>
        <label>e.g. (2018/11/01 to 2018/12/01).</label>
      </div>

      <div>
        <button
          class="btn btn-secondary"
          v-on:click.prevent="filterMovements(
							  searchCategoryType, searchTypeMovement, searchTypePayment,searchByEmailTransfer,
							  fromYear, fromMonth, fromDay, toYear, toMonth, toDay)"
        >Search</button>
        <button class="btn btn-secondary" v-on:click.prevent="cleanFilterValues()">Clean Search</button>
      </div>
    </div>
    <!--Table with all movements-->
    <div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Type Movement</th>
            <th>Email</th>
            <th>Type Payment</th>
            <th>Category</th>
            <th>Date</th>
            <th>Start Balance</th>
            <th>End Balance</th>
            <th>Value</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody v-for="movement in movements" :key="movement.id">
          <tr>
            <td data-toggle="modal" data-target="#moreInfoModal" 
				v-on:click.prevent="moreInfo(movement.id, movement.description, movement.source_description, movement.iban, movement.mb_entity_code, movement.mb_payment_reference,movement.transfer,movement.type_payment, movement.transfer_wallet_id)">
				{{movement.id}}
			</td>
            <td>
              <div v-if="movement.type === 'i'">Income</div>
              <div v-if="movement.type === 'e'">Expense</div>
            </td>
            <td>
              <div v-if="movement.transfer == '1'">{{movement.email}}</div>
              <div v-else></div>
            </td>
            <td>{{movement.type_payment}}</td>
            <td>{{movement.name}}</td>
            <td>{{movement.date}}</td>
            <td>{{movement.start_balance}}€</td>
            <td>{{movement.end_balance}}€</td>
            <td>{{movement.value}}€</td>
            <td>
              <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#editModal" 
			  	v-on:click.prevent="editValues(movement.description, movement.category_id, movement.id, movement.type)">
			  	Edit
			  </button>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#moreInfoModal"
                v-on:click.prevent="moreInfo(movement.id, movement.description, movement.source_description, movement.iban, movement.mb_entity_code, movement.mb_payment_reference,movement.transfer,movement.type_payment, movement.transfer_wallet_id)">
				More
			</button>
            </td>
          </tr>
        </tbody>
      </table>
      <!--PAGINATION-->
      <div class="pagination">
        <button class="btn btn-secondary" v-on:click.prevent="fetchPaginateMovements(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">Previous</button>
        <span>Page {{pagination.current_page}} of {{pagination.last_page}}</span>
        <button
          class="btn btn-secondary"
          v-on:click.prevent="fetchPaginateMovements(pagination.next_page_url)"
          :disabled="!pagination.next_page_url"
        >Next</button>
      </div>
    </div>
    <!--POPUP EDIT MOVEMENT-->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Movement {{movementId}}:</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="inputDescription">Description</label>
              <textarea class="form-control" v-model="description"></textarea>
            </div>
            <div class="form-group">
              <label for="inputType">Category</label>
              <select class="form-control" v-model="typeCategory">
                <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button
              class="btn btn-primary"
              v-on:click.prevent="registerEditValues(description, typeCategory)"
              data-dismiss="modal"
            >Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!--POPUP More Info-->
    <div class="modal fade" id="moreInfoModal" tabindex="-1" role="dialog" aria-labelledby="moreInfoModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="moreInfoModalLabel">Movement {{movementId}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div v-if="photo != null">
              <img v-bind:src="'/storage/fotos/'+photo" height="60px" width="auto" />
            </div>
            <div class="form-group">
              <label>Description: {{description}}</label>
            </div>
            <div class="form-group">
              <label>Source Description: {{sourceDescription}}</label>
            </div>
            <div class="form-group" v-if="typePayment ==='bt'">
              <label>IBAN: {{iban}}</label>
            </div>
            <div class="form-group" v-if="typePayment ==='mb'">
              <label>MB Entity Code: {{mbCode}}</label>
              <br />
              <label>MB Payment Reference: {{mbRef}}</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: function() {
    return {
      allCategories: {},
      searchCategoryType: "",
      searchTypeMovement: "",
      searchTypePayment: "",
      searchByEmailTransfer: "",
      fromYear: "",
      toYear: "",
      fromMonth: "",
      toMonth: "",
      fromDay: "",
      toDay: "",
      categories: {},
      movements: {},
      wallets: {},
      description: "",
      typeCategory: "",
      movementId: "",
      typeofmsg: "",
      showMessage: false,
      message: "",
      showFilter: false,
      pagination: {},
      url: "api/getmovementbywalletid/" + this.$store.state.user.id,
      sourceDescription: "",
      iban: "",
      mbCode: "",
      mbRef: "",
      photo: null,
      typePayment: ""
    };
  },
  methods: {
    loadExpenseCategories() {
      axios.get("api/getexpensecategory").then(({ data }) => (this.categories = data));
    },
    loadIncomeCategories() {
      axios.get("api/getIncomeCategory").then(({ data }) => (this.categories = data));
    },
    loadAllCategories() {
      axios.get("api/getAllCategories").then(({ data }) => (this.allCategories = data));
    },
    loadMyCurrentBalance() {
      axios.get("api/getcurrentbalance/" + this.$store.state.user.id).then(({ data }) => (this.wallets = data));
    },
    loadMyMovements() {
      let $this = this;
      axios.get(this.url).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
    },
    makePagination(data) {
      let pagination = {
        current_page: data.current_page,
        last_page: data.last_page,
        next_page_url: data.next_page_url,
        prev_page_url: data.prev_page_url
      };
      this.pagination = pagination;
    },
    fetchPaginateMovements(url) {
      this.url = url;
      this.loadMyMovements();
    },
    filterData() {
      this.showFilter = true;
    },
    filterMovements(idCategory, typeMovement, typePayment, emailTransfer, fromYear, fromMonth, fromDay, toYear, toMonth, toDay) {
      let $this = this;
      //category
      if (idCategory != "" && typeMovement === "" && typePayment === "" && emailTransfer === "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByCategory/"+this.$store.state.user.id+"/"+idCategory).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //typeMovement
      if (idCategory === "" && typeMovement != "" && typePayment === "" && emailTransfer === "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByTypeMovement/"+this.$store.state.user.id+"/"+typeMovement).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //typeMovement and typeCategory
      if (idCategory != "" && typeMovement != "" && typePayment === "" && emailTransfer === "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByTypeMovementAndCategory/"+this.$store.state.user.id+"/"+typeMovement+"/"+idCategory).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //typePayment
      if (idCategory === "" && typeMovement === "" && typePayment != "" && emailTransfer === "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByTypePayment/"+this.$store.state.user.id+"/"+typePayment).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //typePayment and typeCategory
      if (idCategory != "" && typeMovement === "" && typePayment != "" && emailTransfer === "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByTypePaymentAndCategory/"+this.$store.state.user.id+"/"+typePayment+"/"+idCategory).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //typePayment and typeMovement
      if (idCategory === "" && typeMovement != "" && typePayment != "" && emailTransfer === "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByTypePaymentAndTypeMovement/"+this.$store.state.user.id+"/"+typePayment+"/"+typeMovement).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //typePayment and typeMovement and typeCategory
      if (idCategory != "" && typeMovement != "" && typePayment != "" && emailTransfer === "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByTypePaymentAndCategoryAndTypeMovement/"+this.$store.state.user.id+"/"+typePayment+"/"+idCategory+"/"+typeMovement).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //emailTransfer
      if (idCategory === "" && typeMovement === "" && typePayment === "" && emailTransfer != "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByEmailTransfer/"+this.$store.state.user.id+"/"+emailTransfer).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //emailTransfer and typeCategory
      if (idCategory != "" && typeMovement === "" && typePayment === "" && emailTransfer != "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByEmailTransferAndCategory/"+this.$store.state.user.id+"/"+emailTransfer+"/"+idCategory).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //emailTransfer and typeMovement
      if (idCategory === "" && typeMovement != "" && typePayment === "" && emailTransfer != "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByEmailTransferAndTypeMovement/"+this.$store.state.user.id+"/"+emailTransfer+"/"+typeMovement).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //emailTransfer and typeCategory and typeMovement
      if (idCategory != "" && typeMovement != "" && typePayment === "" && emailTransfer != "" && fromYear === "" && fromMonth === "" && fromDay === "" && toYear === "" && toMonth === "" && toDay === "") {
        axios.get("api/searchByEmailTransferAndCategoryAndTypeMovement/"+this.$store.state.user.id+"/"+emailTransfer+"/"+idCategory+"/"+typeMovement).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //date
      if (idCategory === "" && typeMovement === "" && typePayment === "" && emailTransfer === "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDate/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }

      //date and typecategory
      if (idCategory != "" && typeMovement === "" && typePayment === "" && emailTransfer === "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndCategory/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+idCategory).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //date and typeMovement
      if (idCategory === "" && typeMovement != "" && typePayment === "" && emailTransfer === "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndTypeMovement/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+typeMovement).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //date and typePayment
      if (idCategory === "" && typeMovement === "" && typePayment != "" && emailTransfer === "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndTypePayment/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+typePayment).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //date and emailTransfer
      if (idCategory === "" && typeMovement === "" && typePayment === "" && emailTransfer != "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndEmailTansfer/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+emailTransfer).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }

      //date and typeCategory and typeMovement
      if (idCategory != "" && typeMovement != "" && typePayment === "" && emailTransfer === "" && fromYear != "" && fromMonth != "" && fromDay != "" &&  toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndCategoryAndTypeMovement/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+idCategory+"/"+typeMovement).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }

      //date and typeCategory and typePayment
      if (idCategory != "" && typeMovement === "" && typePayment != "" && emailTransfer === "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndCategoryAndTypePayment/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+idCategory+"/"+typePayment).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //date and typeCategory and emailTransfer
      if (idCategory != "" && typeMovement === "" && typePayment === "" && emailTransfer != "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndCategoryAndEmailTransfer/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+idCategory+"/"+emailTransfer).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }

      //date and typeCategory and typeMovement and typePayment
      if (idCategory != "" && typeMovement != "" && typePayment != "" && emailTransfer === "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndCategoryAndTypeMovementAndTypePayment/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+idCategory+"/"+typeMovement+"/"+typePayment).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }

      //date and typeCategory and typeMovement and emailTransfer
      if (idCategory != "" && typeMovement != "" && typePayment === "" && emailTransfer != "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndCategoryAndTypeMovementAndEmailTransfer/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+idCategory+"/"+typeMovement+"/"+emailTransfer).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //date and typeMovement and typePayment
      if (idCategory === "" && typeMovement != "" && typePayment != "" && emailTransfer === "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndTypeMovementAndTypePayment/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+typeMovement+"/"+typePayment).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
      //date and typeMovement and emailTransfer
      if (idCategory === "" && typeMovement != "" && typePayment === "" && emailTransfer != "" && fromYear != "" && fromMonth != "" && fromDay != "" && toYear != "" && toMonth != "" && toDay != "") {
        axios.get("api/searchByDateAndTypeMovementAndEmailTransfer/"+this.$store.state.user.id+"/"+fromYear+"-"+fromMonth+"-"+fromDay+"/"+toYear+"-"+toMonth+"-"+toDay+"/"+typeMovement+"/"+emailTransfer).then(({ data }) => ((this.movements = data.data), $this.makePagination(data)));
      }
    },
    cleanFilterValues() {
      this.searchCategoryType = "";
      this.searchTypeMovement = "";
      this.searchTypePayment = "";
      this.searchByEmailTransfer = "";
      this.fromYear = "";
      this.toYear = "";
      this.fromMonth = "";
      this.toMonth = "";
      this.fromDay = "";
      this.toDay = "";
      this.showFilter = false;
      this.loadMyMovements();
    },
    moreInfo(currentMovement, currentDescription, currentSourceDescription, currentIban, currentMbCode, currentMbReference, currentTransfer, currentTypePayment, currentPhoto) {
      this.description = currentDescription;
      this.sourceDescription = currentSourceDescription;
      this.movementId = currentMovement;
      this.typePayment = currentTypePayment;
      if (currentTransfer == 1) {
        axios.get('api/users/'+currentPhoto).then(response => {this.photo=response.data.data.photo});
      }
      if (currentTypePayment === "bt") {
        this.iban = currentIban;
      }
      if (currentTypePayment === "mb") {
        this.mbCode = currentMbCode;
        this.mbRef = currentMbReference;
      }
    },
    editValues(currentDiscription, currentCategory, currentMovement, typeMovement) {
      this.description = currentDiscription;
      this.typeCategory = currentCategory;
      this.movementId = currentMovement;
      if (typeMovement === "e") {
        this.loadExpenseCategories();
      } else {
        this.loadIncomeCategories();
      }
    },
    registerEditValues(description, category) {
      axios
        .put("api/movements/" + this.movementId, {
          description: description,
          category_id: category
        })
        .then(response => {
          this.typeofmsg = "alert-success";
          this.message = "Movement edited with success";
          this.showMessage = true;
          this.loadMyMovements();
        })
        .catch(error => {
          this.typeofmsg = "alert-danger";
          this.message = "Error editing movement";
          this.showMessage = true;
        });
    }
  },
  created() {
    this.loadMyCurrentBalance();
    this.loadMyMovements();
    this.loadAllCategories();
  }
};
</script>
<style>
.myWallet {
  display: inline-block;
}

.filters .date input {
  width: 10%;
}
</style>