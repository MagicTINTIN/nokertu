<script>
const DEBUGVAL = <?php echo $DEBUGMODE ?>;

function logger(tolog, type = 2) {
    if (DEBUGVAL % type == 0) {
        console.log(tolog);
    }
}

function errLogger(tolog, err, type = 2) {
    if (DEBUGVAL % type == 0) {
        console.error(tolog, err);
    }
}

</script>