import Vue from 'vue';
import Chart from 'chart.js';
import axios from 'axios';

new Vue({
    el: '#app',
    data: {
        //FORMAT DATA YANG AKAN DIGUNAKAN KE CHART.JS
        testChartData: {
            //TYPE CHARTNYA line
            type: 'bar',
            data: {
                //YANG PERLU DIPERHATIKAN BAGIAN LABEL INI NILAINYA DINAMIS
                labels: [],
                datasets: [{
                    label: 'Total Penjualan',
                    //DAN NILAI DATA JUGA DINAMIS TERGANTUNG DATA YANG DITERIMA DARI SERVER
                    data: [],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(54, 162, 235, 0.5)'
                    ],
                    borderColor: [
                        '#007BFF',
                        '#007BFF',
                        '#007BFF',
                        '#007BFF',
                        '#007BFF',
                        '#007BFF',
                        '#007BFF'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                lineTension: 1,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 25,
                            max: 20,
                            min: 0,
                            stepSize: 5
                        }
                    }]
                }
            }
        }
    },

    mounted() {
        // Jalankan method getData dan createChart dengan parameter chart
        this.getData();
        this.createChart('test-chart', this.testChartData);
    },

    methods: {
        // Method createChart dengan 2 parameter
        createChart(chartId, chartData) {
            // Cari elemen dgn id yang sama dengan parameter chartId
            const ctx = document.getElementById(chartId);
            // Definisikan Chart.js
            const myChart = new Chart(ctx, {
                type: chartData.type,
                data: chartData.data,
                options: chartData.options,
            });
        },

        // Method getData untuk minta data dari serve
        getData() {
            //MENGIRIMKAN PERMINTAAN DENGAN ENDPOINT /api/chart
            axios.get('/api/chart')
                // Respons
                .then((response) => {
                    //DILOOPING DENGAN MEMISAHKAN KEY DAN VALUE
                    Object.entries(response.data).forEach(
                        ([key, value]) => {
                            //DIMANA KEY (BACA: DALAM HAL INI INDEX DATA ADALAH TANGGAL)
                            //KITA MASUKKAN KEDALAM testChartData > data > labels
                            this.testChartData.data.labels.push(key);
                            //KEMUDIAN VALUE DALAM HAL INI TOTAL PESANAN
                            //KITA MASUKKAN KE DALAM testChartData > data > datasets[0] > data
                            this.testChartData.data.datasets[0].data.push(value);
                        }
                    );
                })
        }
    }
})
