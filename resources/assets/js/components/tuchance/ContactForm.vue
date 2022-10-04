<template>
  <div class="bg-white font-lightp-4 relative w-xs sm:w-sm form-inner md:w-lg lg:w-2xl xl:w-4xl text-sm md:text-lg text-gray-800 mx-auto">
    <form-field label-class="text-xl font-medium block" name="name" label="Nombre">
      <input v-validate="{required: true}" class="form-field" type="text" v-model="data.name" name="name">
    </form-field>
    <div class="-mx-3 flex flex-col md:flex-row">
      <div class="px-3 w-full md:w-1/2">
        <form-field label-class="text-xl font-medium block" name="email" label="Correo Electrónico">
          <input v-validate="{required: true}" class="form-field" type="email" v-model="data.email" name="email">
        </form-field>
      </div>
      <div class="px-3 w-full md:w-1/2">
        <form-field label-class="text-xl font-medium block" name="phone" label="Teléfono">
          <input v-validate="{required: true}" class="form-field" type="text" v-model="data.phone" name="phone">
        </form-field>
      </div>
    </div>
    <form-field label-class="text-xl font-medium block" name="comments" label="Mensaje">
      <textarea v-validate="{required: true}" class="form-field" v-model="data.message" name="comments"></textarea>
    </form-field>
    <div class="text-right">
      <button type="submit" class="btn bg-teal w-full md:w-auto text-white hover:bg-primary-light transition-all rounded-none" :class="{'opacity-50': busy}" @click.prevnet="submit">{{busy?'Enviando':'Enviar'}}</button>
    </div>
  </div>
</template>

<script>

  export default {
    props: {
      url: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        busy: false,
        data: {
          name: '',
          email: '',
          phone: '',
          message: ''
        }
      };
    },
    methods: {
      async submit() {
        const valid = await this.$validator.validate();

        if (!valid || this.busy) {
          return;
        }

        this.busy = true;

        try {
          await this.$http.post(this.url, this.data);

          this.$notify({
            type: 'success',
            title: 'Éxito',
            text: '¡Nos pondremos en contacto contigo muy pronto!'
          });

          this.data.name = '';
          this.data.email = '';
          this.data.phone = '';
          this.data.message = '';

          await this.$validator.reset();

        } catch (e) {
          await this.$notify({
            icon: 'error',
            title: 'Lo sentimos',
            text: 'No pudimos enviar tu solicitud de contacto, por favor verifica los datos ingresados e intenta nuevamente'
          });
        } finally {
          this.busy = false;
        }
      }
    }
  }
</script>
