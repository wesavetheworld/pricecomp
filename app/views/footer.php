</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<!-- Modal -->
<div id="pc-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.load-modal').on('click',
            function(e) {
                e.preventDefault();
                $('.modal-content').load($(this).attr('modal-url'),function(result){
                    $('#pc-modal').modal({
                        show: true
                    });
                });
            }
        );
    });
</script>

</body>
</html>
