<template>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
		
		props: {
			credit_id:{
				type: Number, 
				required: false,
			},
		},
		
        /**
         * Prepare the component (Vue 1.x).
         */
		mounted() {
             this.getCredit();
        },
        /**
         * Prepare the component (Vue 2.x).
         */
		 methods: {
            getCredit() {
                axios.get('/credit/'+this.credit_id)
                        .then(response => {
                          var x = response.data.invested_amount;
						  $(".class"+this.credit_id).append(x);
                        });
            },
			},
				
		}
		
		
		
   
</script>

