<template src="../views/createAula.html"></template>

<script>
import axios from "axios";
import Aula from "../models/aula.js";
import swal from "sweetalert";
export default {
  name: "editAula",
  data() {
    return {
      isEdit: true,
      aula: new Aula(),
      building: { id: null },
      edifici: null
    };
  },
  mounted() {
    var id = this.$route.params.aula;
    this.getEdifici();
    this.getAula(id);
  },
  methods: {
    save() {
      this.aula.building_id = this.building.id;
      this.aula.name = this.building.name;
      let aulaId = this.$route.params.aula;
      axios
        .put(`http://127.0.0.1:8000/api/aule/${aulaId}`, this.aula)
        .then(res => {
          console.log(this.aula);
          if (res.status == 200) {
            swal({
        text: "L'aula è stata modificata",
        icon: "success"
      });
      this.$router.push("/gestisceAule");
          }
        })
        .catch(e => {
          console.log(e);
        });
      
    },
    getEdifici() {
      axios.get("http://127.0.0.1:8000/api/buildings").then(res => {
        this.edifici = res.data.data;
      });
    },
    getAula(id) {
      axios.get(`http://127.0.0.1:8000/api/classrooms/${id}`).then(res => {
        this.aula = res.data.data;
      });
    }
  }
};
</script>