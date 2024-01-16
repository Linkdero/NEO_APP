
export default {
  name: "insumosc",
  props: {
    userId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      cinemas: [],
      movies: [],
      cinemaId: null,
      loading: false,
      order: {
        scheduleId: null,
        quantity: null
      }
    };
  },
  computed: {

  },
  created: {
    
  },
  components: {},
  methods: {
    message: function(){
    this.loading = true;
    }
  },
  mounted() {

  }
};
