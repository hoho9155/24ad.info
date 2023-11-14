<div id="app">
    <div v-if="loading">Loading...</div>
    
    <div>Current Country: @{{ current_country }}</div>
    <div>(@{{ processed_countries }}/@{{ countries.length }})</div>
    <div>Error Count: @{{ processed_errors }}</div>
    
    <div>Current City: @{{ current_city_name?.en }}</div>
    <div>(@{{ processed_cities }}/@{{ cities.length }})</div>
    <br />
    <button @click="start" :disabled="loading || running">Start</button>
    <button @click="stop" :disabled="!running">Stop</button>
</div>



<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>

<script>
    const languages = {
        "DK": "da",
        "AE": "ar",
        "AR": "es",
        "AT": "de",
        "AU": "en",
        "BA": "bs,hr,sr",
        "BE": "nl,fr,de",
        "BG": "bg",
        "BR": "pt",
        "CA": "fr",
        "CH": "de,fr,it,rm",
        "CN": "zh",
        "CZ": "cs",
        "DE": "de",
        "EE": "et",
        "ES": "es",
        "CA": "fr",
        "FR": "fr",
        "GE": "ka",
        "GI": "en",
        "HU": "hu",
        "IT": "it",
        "JM": "en",
        "LI": "de",
        "LT": "lt",
        "LU": "lb,de,fr",
        "MT": "mt",
        "NZ": "mi",
        "PH": "fil",
        "PL": "pl",
        "SE": "sv",
        "SI": "sl",
        "SK": "sk",
        "SM": "it",
        "TR": "tr",
        "US": "en",
        "VA": "la,it",
        "VE": "es",
        "VG": "en",
        "VI": "en",
        "GB": "en",
    };


    var app = new Vue({
        el: '#app',
        data: {
            loading: true,
            running: false,
            countries: [],
            processed_countries: 0,
            current_country: null,
            cities: [],
            current_city: null,
            current_city_name: null,
            processed_cities: 0,
            processed_errors: 0,
        },
        async mounted() {
            this.countries = await fetch('/postal-codes/translate/city/countries').then(response => response.json());
            console.log('Countries:', this.countries);
            this.loading = false;
        },
        methods: {
            async start() {
                this.running = true;
                this.processed_countries = 0;
                
                try {
                    for (const c of this.countries) {
                        if (!this.running) break;
                        
                        this.current_country = c.code;
                        this.cities = await fetch('/postal-codes/translate/city/cities?country_code=' + c.code).then(response => response.json());
                        this.processed_cities = 0;
                        
                        const lang_code = languages[this.current_country];
                        if (!lang_code) break;
                        if (lang_code !== "en") {
                            for (const city of this.cities) {
                                if (!this.running) break;
                                this.current_city = city;
                                this.current_city_name = JSON.parse(city.name);
                                
                                if (Object.keys(this.current_city_name).length === 1) {
                                    let flag = true;
                                    while (flag) {
                                        try {
                                            const response = await fetch('/postal-codes/translate/city/translate?id=' + city.id + '&name=' + this.current_city_name.en + '&country_code=' + this.current_country + '&lang_code=' + lang_code).then(response => response.json());    
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
                                }
                                
                                this.processed_cities++;
                            } 
                        }
                        
                        this.processed_countries++;
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