<footer>
        <?php if (isset($is_index) && $is_index === true): ?>
            <div >
                <section >
                    <h4 >Contact the Gossip</h4>
                    <p>📍 123 Rue du vice, 95000 Cergy</p>
                    <p>📞 01 23 45 67 89</p>
                    <p>✉️ <a href="mailto:SipandGossip@gmail.com">hello@gossipbrunch.fr</a></p>
                </section>

                <section>
                    <h4 >Projet Creative-Yumland</h4>
                    <p>Filière : préING2 </p>
                    <p>Année : 2025-2026 </p>
                    <p>Auteurs : DECHAMPS & LECHEVALLIER & NANCHUGONG </p>
                </section>
            </div>
            <div>
                <p>&copy; 2026 Spotted: The Brunch - XOXO</p>
            </div>

        <?php else: ?>
            <div >
                <p >&copy; 2026 SIP AND SPILL</p>
            </div>
        <?php endif; ?>
    </footer>
    <?php if (isset($load_validation) && $load_validation === true): ?>
        <script src="./js/validation.js"></script>
    <?php endif; ?>


    <script src="./js/theme.js"></script>
</body>
</html>