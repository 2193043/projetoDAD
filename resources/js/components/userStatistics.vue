<template>
    <div>
        <h1>Movements Statistics</h1>
        <br>
        <div>
            <h4>Expenses Movements:</h4>
            <table class="table table-striped">
				<thead>
					<tr>
						<th>Total Movements</th>
						<th>Total Spent</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{expensesMovements}}</td>
						<td>{{expensesValue}}€</td>
					</tr>
				</tbody>
			</table>
        </div>
        <div>
            <h4>Income Movements:</h4>
            <table class="table table-striped">
				<thead>
					<tr>
						<th>Total Movements</th>
						<th>Total Earned</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{incomeMovements}}</td>
						<td>{{incomeValue}}€</td>
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
                expensesMovements: "",
                expensesValue: "",
                incomeMovements: "",
                incomeValue: "",
			}
		},
        methods: {
            totalExpensesMovements() {   
                axios.get("api/totalexpensesmovements/"+this.$store.state.user.id)
                .then(response => {
                    this.expensesMovements=response.data;
                });
            },
            totalExpensesValue() { 
                axios.get("api/totalexpenses/"+this.$store.state.user.id)
                .then(response => {
                    this.expensesValue=response.data;
                });
            },
            totalIncomeMovements() {   
                axios.get("api/totalincomemovements/"+this.$store.state.user.id)
                .then(response => {
                    this.incomeMovements=response.data;
                });
            },
            totalIncomeValue() { 
                axios.get("api/totalincome/"+this.$store.state.user.id)
                .then(response => {
                    this.incomeValue=response.data;
                });
            },
        },
        created(){
            this.totalExpensesMovements();
            this.totalExpensesValue();
            this.totalIncomeMovements();
            this.totalIncomeValue();
        }
    }

</script>