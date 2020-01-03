<template>
  <div>
      <h1>Virtual Wallet</h1>
    <div class="card mb-4 shadow-sm" >
      <p>Welcome to Virtual Wallet an application that replaces
        a physical wallet, by keeping virtual money and handling small transaction between platform
        users or external entities.</p>
    <p>At the moment the application has {{totalWallets}} virtual wallets.</p>
    <p>If you like this idea click the buttom register and join us.</p>
    </div>
  </div>
</template>
<script type="text/javascript">    
    export default {
        data: function(){
            return { 
                totalWallets: ''
            }
        },
        methods: {
            getTotalWallets(){
              axios.get("api/totalwallets").then(({ data }) => (this.totalWallets = data));
            }
        },
        created(){
            this.getTotalWallets();
        }
    }
</script>
<style>
.card {
    background-color: #cfebd81c;
    padding: 25px;
}

.card p{
    font-size: 17px;
}
</style>