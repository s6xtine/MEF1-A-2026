<footer>
        <?php if (isset($is_index) && $is_index === true): ?>
            <div class="footer-container">
                <section class="footer-contact">
                    <h4 class="titre-footer">Contact the Gossip</h4>
                    <p>📍 123 Rue du vice, 95000 Cergy</p>
                    <p>📞 01 23 45 67 89</p>
                    <p>✉️ <a href="mailto:SipandGossip@gmail.com">hello@gossipbrunch.fr</a></p>
                </section>

                <section class="footer-authors">
                    <h4 class="titre-footer">Projet Creative-Yumland</h4>
                    <p>Filière : préING2 </p>
                    <p>Année : 2025-2026 </p>
                    <p>Auteurs : DECHAMPS & LECHEVALLIER & NANCHUGONG </p>
                </section>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Spotted: The Brunch - XOXO</p>
            </div>

        <?php else: ?>
            <div class="footer-bottom">
                <p class="footer-mentions">&copy; 2026 SIP AND SPILL</p>
            </div>
        <?php endif; ?>
    </footer>
</body>
</html>