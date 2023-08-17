<template>
  <v-container fluid>
    <v-row class="justify-center">
        <v-col>
          <v-text-field
            v-model="number"
            label="Номер квартиры"
            :rules="numberRules"
          ></v-text-field>
        </v-col>

        <v-col>
          <v-text-field
            v-model="floorFrom"
            label="Этаж от"
          ></v-text-field>
        </v-col>

        <v-col>
          <v-text-field
            v-model="floorTo"
            label="Этаж до"
          ></v-text-field>
        </v-col>

        <v-col>
          <v-text-field
            v-model="priceFrom"
            label="Цена от"
          ></v-text-field>
        </v-col>

        <v-col>
          <v-text-field
            v-model="priceTo"
            label="Цена до"
          ></v-text-field>
        </v-col>
    </v-row>

    <v-row class="justify-center">
      <v-col cols="12" md="4" lg="4">
        <v-btn
          @click="getAppartments"
          :loading="loading"
          block
          class="mt-2"
          text="Submit">
          Поиск
        </v-btn>
      </v-col>
    </v-row>
    
    <v-row class="justify-center">
      <v-col cols="12" >
        <v-table>
          <thead>
            <tr>
              <th class="text-left">Номер</th>
              <th class="text-left">Этаж</th>
              <th class="text-left">Цена</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in appartments" :key="item.id">
              <td>{{ item.number }}</td>
              <td>{{ item.floor }}</td>
              <td>{{ item.price }}</td>
            </tr>
          </tbody>
        </v-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  import axios from "axios";

  export default {
    data() {
      return {
        number: '',
        floorFrom: '',
        floorTo: '',
        priceFrom: '',
        priceTo: '',
        appartments: [],
        loading: false,
        limit: 10,
        numberRules: [
          value => {
            if (value && !/\d+/.test(value)) return 'must be an integer'

            return true
          },
        ]
      }
    },

    methods: {
      async getAppartments() {
        try {
          this.loading = true;
          const response = await axios.get('http://localhost:8080?',
            {
              params: {
                number: this.number,
                floor_from: this.floorFrom,
                floor_to: this.floorTo,
                price_from: this.priceFrom,
                price_to: this.priceTo
              }
            }
          );
          if (response.status === 200) {
            this.appartments = response.data;
          }
        } catch (e) {
          console.log(e);
          alert('error');
        } finally {
          this.loading = false;
        }
      }
    },

    mounted() {
      this.getAppartments();
    }
  }
</script>