import Vue from 'vue'
import axios from 'axios'
import VueSweetalert2 from 'vue-sweetalert2';

Vue.filter('currency', function (money) {
    return accounting.formatMoney(money, "Rp ", 2, ".", ",")
})

Vue.use(VueSweetalert2);

new Vue({
    el: '#app',
    data: {
        product: {
            id_product: '',
            sell_price: '',
            product_name: '',
            product_image: ''
        },

        // Tambah cart
        cart: {
            product_id : '',
            qty : 1
        },

        customer: {
            email_customer: ''
        },

        // untuk menampung listcart
        shoppingCart : [],
        submitCart : false,

        formCustomer: false,
        resultStatus: false,
        submitForm: false,
        errorMessage: '',
        message: ''
    },

    watch: {
        //apabila nilai dari product > id berubah maka
        'cart.product_id': function() {
            //mengecek jika nilai dari product > id ada
            if (this.cart.product_id) {
                //maka akan menjalankan methods getProduct
                this.getProduct()
            }
        },

        'customer.email_customer': function() {
            this.formCustomer = false
            if (this.customer.name_customer != '') {
                this.customer = {
                    name_customer: '',
                    phone_customer: '',
                    address_customer: ''
                }
            }
        }
    },

    //menggunakan library select2 ketika file ini di-load
    mounted() {
        $('#product_id').select2({
            width: '100%'
        }).on('change', () => {
            //apabila terjadi perubahan nilai yg dipilih maka nilai tersebut
            //akan disimpan di dalam var product > id
            this.cart.product_id = $('#product_id').val();
        });

        //panggil method getCart() untuk load cookie cart
        this.getCart()
    },

    methods: {
        getProduct() {
            //fetch ke server menggunakan axios dengan mengirimkan parameter id
            //dengan url /api/product/{id}
            axios.get(`/api/product/${this.cart.product_id}`)
                .then((response) => {
                //assign data yang diterima dari server ke var product
                this.product = response.data
            })
        },

        //method untuk menambahkan produk kedalam cart
        addToCart(){
            this.submitCart = true;

            //send data ke server
            axios.post('/api/cart', this.cart)
                .then((response) => {
                    setTimeout(() => {
                        //success, data disimpan dalam variable shoppingCart
                        this.shoppingCart = response.data

                        //mengkosongkan variable
                        this.cart.product_id = ''
                        this.cart.qty = 1
                        this.product = {
                            id_product: '',
                            sell_price: '',
                            product_name: '',
                            product_image: ''
                        }
                        $('#product_id').val('')
                        this.submitCart = false
                    }, 2000)
                })
                .catch((error) => {

                })
        },

        //method ambilt list cart yang telah disimpan
        getCart(){
            //fetch data ke server
            axios.get('/api/cart')
                .then((response) => {
                    //data yang diterima disimpan dalam variable shoppingCart
                    this.shoppingCart = response.data
                })
            },

        //hapus cart
        removeCart(id){
            //tampilkan konfirmasi dengan sweetalert2
            this.$swal({
                title: 'Kamu Yakin?',
                text: 'Data yang telah dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan!',
                cancelButtonText: 'Batalkan!',
                showCloseButton: true,
                showLoaderOnConfirm: true,
                preConfirm: ()=> {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            resolve()
                        }, 2000)
                    })
                },
                allowOutsideClick: () => !this.$swal.isLoading()
            }).then ((result) => {
                // apabila setuju
                if(result.value){
                    //kirim data keserver
                    axios.delete('/api/cart/' + id)
                        .then ((response) => {
                            // load cart baru
                            this.getCart();
                        })
                        .catch((error) => {
                            console.log(error);
                        })
                }
            })
        }, 

        searchCustomer() {
            axios.post('/api/customer/search', {
                email: this.customer.email_customer
            })
            .then((response) => {
                if (response.data.status == 'success') {
                    this.customer = response.data.data
                    this.resultStatus = true
                } 
                this.formCustomer = true
            })
            .catch((error) => {
                console.log(error);
            })
        },

        
       sendOrder() {
            //Mengosongkan var errorMessage dan message
            this.errorMessage = ''
            this.message = ''
            
            //jika var customer.email dll tidak kosong
            if (this.customer.email_customer != '' && this.customer.name_customer != '' && this.customer.phone_customer != '' && this.customer.address_customer != '') {
                //maka akan menampilkan kotak dialog konfirmasi
                this.$swal({
                    title: 'Kamu Yakin?',
                    text: 'Kamu Tidak Dapat Mengembalikan Tindakan Ini!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan!',
                    cancelButtonText: 'Batalkan!',
                    showCloseButton: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return new Promise((resolve) => {
                            setTimeout(() => {
                                resolve()
                            }, 2000)
                        })
                    },
                    allowOutsideClick: () => !this.$swal.isLoading()
                }).then ((result) => {
                    //jika di setujui
                    if (result.value) {
                        //maka submitForm akan di-set menjadi true sehingga menciptakan efek loading
                        this.submitForm = true
                        //mengirimkan data dengan uri /checkout
                        axios.post('/checkout', this.customer)
                        .then((response) => {
                            setTimeout(() => {
                                //jika responsenya berhasil, maka cart di-reload
                                this.getCart();
                                //message di-set untuk ditampilkan
                                this.message = response.data.message
                                //form customer dikosongkan
                                this.customer = {
                                    name_customer: '',
                                    phone_customer: '',
                                    address_customer: ''
                                }
                                //submitForm kembali di-set menjadi false
                                this.submitForm = false
                            }, 1000)
                        })
                        .catch((error) => {
                            console.log(error)
                        })
                    }
                })
            } else {
                //jika form kosong, maka error message ditampilkan
                this.errorMessage = 'Masih ada inputan yang kosong!'
            }
        }
    }
})
