<template>
    <div class="text-white mb-8">
       <div class="places-input text-gray ">
            <input 
            class="bg-gray-200 appearance-none border-2 border-gray-200 
                rounded w-full py-2 px-4 text-gray-700 leading-tight 
                focus:outline-none focus:bg-white focus:border-teal-500" 
            id="address-input" type="search" placeholder="Search City/Country">
            <p class="block text-gray-700 text-sm font-bold mb-2">Selected City <span id="address-value" class="text-smibold text-muted">none</span></p>
       </div>
       <div class="weather-container font-sans w-128 rounded-lg overflow-hidden bg-gray-900 shadow-lg mt-4">
           <div class="current-weather flex items-center  justify-between px-6 py-8">
               <div class="flex items-center">
                   <div>
                       <div class="text-6xl font-semibold">{{ currentTemparute.actual}}°C</div>
                       <div>Feels like {{ currentTemparute.feels}}°C</div>
                   </div>
                   <div class="mx-5">
                       <div class="font-semibold">{{ currentTemparute.summary}}</div>
                       <div >{{ location.name }}</div>
                   </div>
               </div>
               <div>
                   <canvas id="iconCurrent" width="96" height="96"></canvas>
               </div>
           </div> <!-- current weather end -->

           <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">

                <div 
                    v-for="(day, index) in daily" 
                    :key="daily.key" 
                    :class="{'mt-8': index > 0}" 
                    class="flex items-center"
                    v-if="index < 7"
                >
                   <div class="w-1/6 text-lg text-gray-200">{{ convertDay(day.time)}}</div>
                   <div class="w-4/6 px-4 flex items-center">
                        <div><canvas :id="`icon${index+1}`" :data-icon="formatCase(day.icon)" width="24" height="24"></canvas></div>
                        <div class="ml-3">{{day.summary}}</div>
                   </div>
                   <div class="w-1/6 text-right">
                        <div class="text-yellow-200">{{Math.round(day.apparentTemperatureHigh)}}°C </div>
                        <div class="text-green-200">{{Math.round(day.apparentTemperatureLow)}}°C</div>
                   </div>
               </div>
           </div> <!-- end future weather -->

       </div> <!-- end weather container -->
    </div>
</template>

<script>
import places from 'places.js'
    export default {
        watch: {
            location: {
                handler(newValue, oldValue) {
                    this.fetchData()
                },
                deep: true
            }
        },
        data() {
            return {
                currentTemparute: {
                    actual :'',
                    feels: '',
                    summary: '',
                    icon: ''
                },
                daily: [],
                location: {
                    name: '',
                    lat: null,
                    long: null
                },
                initial: true
                
            }
        },
        mounted() {
             this.fetchData();

            const placesAutocomplete = places({
                appId: 'plTG3YBN2IP3',
                apiKey: '2f9fcc0ecefd8787fa16b91a4724a3d3',
                container: document.querySelector('#address-input')
            }).configure({
                type: 'city',
                aroundLatLngViaIP: false
            });

            const $address =document.querySelector('#address-value')
            placesAutocomplete.on('change', (e) => {
                $address.textContent = e.suggestion.value
                this.location.name = `${e.suggestion.name}, ${e.suggestion.country}`
                this.location.lat = e.suggestion.latlng.lat
                this.location.long = e.suggestion.latlng.lng
            });

            placesAutocomplete.on('clear', function() {
                $address.textContent = 'none';
            });

           
            
        },
        methods: {
            fetchData() {
                this.initial = false;
                const skycons = new Skycons({'color': 'white'});

                fetch(`/api/weather?lat=${this.location.lat}&long=${this.location.long}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    this.currentTemparute.actual = Math.round(data.currently.temperature)
                    this.currentTemparute.feels = Math.round(data.currently.apparentTemperature)
                    this.currentTemparute.summary = data.currently.summary
                    this.currentTemparute.icon = this.formatCase(data.currently.icon)

                    this.daily = data.daily.data

                    skycons.add("iconCurrent", this.currentTemparute.icon);
                    skycons.play();
                    this.$nextTick(() => {
                        
                        skycons.add("icon1", document.getElementById('icon1').getAttribute('data-icon'));
                        skycons.add("icon2", document.getElementById('icon2').getAttribute('data-icon'));
                        skycons.add("icon3", document.getElementById('icon3').getAttribute('data-icon'));
                        skycons.add("icon4", document.getElementById('icon4').getAttribute('data-icon'));
                        skycons.add("icon5", document.getElementById('icon5').getAttribute('data-icon'));
                        skycons.add("icon6", document.getElementById('icon6').getAttribute('data-icon'));
                        skycons.add("icon7", document.getElementById('icon7').getAttribute('data-icon'));
                        skycons.add("icon7", document.getElementById('icon7').getAttribute('data-icon'));
                        // skycons.add("icon8", document.getElementById('icon8').getAttribute('data-icon'));

                        skycons.play()
                        
                    })
                })

            },
            formatCase(string) {
                return string.split(' ').join('-');
            },
            convertDay(time){
                const newDate = new Date(time*1000)
                const days = ['SUN', 'MON', 'TUES', 'WED', 'THU', 'FRI', 'SAT'];

                return days[newDate.getDay()];
            }
        }
    }
</script>
