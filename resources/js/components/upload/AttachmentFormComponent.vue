<template>
    <div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            files: [],
        }
    },
    methods: {
        submitFiles() {
            let formData = new FormData();
            for( let i = 0; i < this.$refs.file.files.length; i++ ){
                let file = this.$refs.file.files[i];
                formData.append('files[' + i + ']', file);
            }

            axios.post('/api/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(response => {
                this.files = response.data;
            })
                .catch(function(){
                });
        },
    },
}
</script>
<style scoped>

</style>
