<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">

            <div class="flex">
                <div class="mt-2 flex flex-col justify-start items-start" style="margin-right: 50px">
                    <div class="text-black text-md font-semibold my-2">Couleur</div>
                    <div v-for="color in field.colors" class="flex my-2">
                        <input @click="checkColor($event, color)" :checked="colors.findIndex(c => c.label === color.label) > -1" :value="color"  class="mr-2 leading-tight" type="checkbox">
                        <div class="text-sm"> {{ color.label }} </div>
                    </div>
                </div>

                <div class="mt-2 flex flex-col justify-start items-start">
                    <div class="text-black text-md font-semibold my-2">Tailles</div>
                    <div v-for="size in field.sizes" class="flex my-2">
                        <input @click="checkSize($event, size)" :checked="sizes.findIndex(s => s.label === size.label) > -1" :value="size"  class="mr-2 leading-tight" type="checkbox">
                        <div class="text-sm"> {{ size.label }} </div>
                    </div>
                </div>
            </div>
        </template>
    </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data(){
        return {
            colors : [],
            sizes : []
        }
    },

    mounted() {
        this.colors = this.field.selectedColors;
        this.sizes = this.field.selectedSizes;
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            this.value = JSON.stringify( { colors : this.colors , sizes : this.sizes } );
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },

        checkColor(event, color){
            let index = this.colors.findIndex(c => c.label === color.label);
            if(index > -1)
                this.colors.splice(index, 1)
            else
                this.colors.push(color)
        },

        checkSize(event, size){
            let index = this.sizes.findIndex(s => s.label === size.label);
            if(index > -1)
                this.sizes.splice(index, 1)
            else
                this.sizes.push(size)
        }
    },
}
</script>
