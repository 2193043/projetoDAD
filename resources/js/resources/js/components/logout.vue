<template>
    <div>

    </div>
</template>

<script type="text/javascript">    
    export default {
        methods: {
            logout() {
                axios.post('api/logout')
                    .then(response => {
                        this.$store.commit('clearUserAndToken');
                        this.$router.push('/');
                    })
                    .catch(error => {
                        this.$store.commit('clearUserAndToken');
                        console.log(error);
                    })            
                }
        },
        created(){
            this.logout();
        }
    }
</script>