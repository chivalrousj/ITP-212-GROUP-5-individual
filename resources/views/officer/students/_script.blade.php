{{-- Makes the program dropdown show only the programs of the chosen institute --}}
<script>
    function linkSelects(instituteId, programId) {
        const institute = document.getElementById(instituteId);
        const program = document.getElementById(programId);

        // keep a copy of every program option, with the institute it belongs to
        const all = Array.from(program.options).map(function (o) {
            return { value: o.value, text: o.text, institute: o.dataset.institute || '' };
        });

        function rebuild() {
            const current = program.value;
            program.innerHTML = '';

            all.forEach(function (o) {
                // keep the placeholder, and the programs of the chosen institute
                if (o.value === '' || institute.value === '' || o.institute === institute.value) {
                    const option = new Option(o.text, o.value);
                    option.dataset.institute = o.institute;
                    if (o.value === current) option.selected = true;
                    program.add(option);
                }
            });
        }

        institute.addEventListener('change', rebuild);
        rebuild();
    }
</script>
