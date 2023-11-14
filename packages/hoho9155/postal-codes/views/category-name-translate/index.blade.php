<div id="app">
    <div v-if="loading">Loading...</div>
    <input type="number" v-model="processed_categories" />

    <div>Category: @{{ current_category }}</div>
    <div>(@{{ processed_categories }}/@{{ categories.length }})</div>
    <div>Error Count: @{{ processed_errors }}</div>
    
    <button @click="start" :disabled="loading || running">Start</button>
    <button @click="stop" :disabled="!running">Stop</button>
</div>



<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>

<script>
  
    var app = new Vue({
        el: '#app',
        data: {
            loading: true,
            running: false,
            categories: [],
            processed_categories: 0,
            current_category: null,
            current_category_name: null,
            processed_errors: 0,
        },
        async mounted() {
            this.categories = await fetch('/postal-codes/translate/category/categories').then(response => response.json());
            console.log('Categories:', this.categories);
            this.loading = false;
        },
        methods: {
            async start() {
                this.running = true;
                this.processed_errors = 0;
                
                try {
                    let i = 0;
                    for (const c of this.categories) {
                        if (!this.running) break;
                        i++;
                        if (i < this.processed_categories) continue;

                        this.current_category_name = JSON.parse(c.name);
                                                        
                        let flag = true;
                        while (flag && this.running) {
                            try {
                                const response = await fetch('/postal-codes/translate/category/translate?id=' + c.id + '&name=' + encodeURIComponent(this.current_category_name.en)).then(response => response.json());    
                                console.log('API response:', response);
                                flag = false;
                                if (response == "Error") {
                                    this.processed_errors++;
                                    break;
                                }
                            } catch (err) {
                                console.log(err);
                            }
                        }
                        this.processed_categories++;
                        
                    }
                } catch (err) {
                    console.log(err);
                    // setTimeout(this.start, 10 * 60 * 1000);
                }
            },
            async stop() {
                this.running = false;
            }
        }
    });
</script>