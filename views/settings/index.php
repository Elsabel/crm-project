<div class="mb-4">
    <h2>Settings</h2>
    <p class="text-muted">Configure your CRM system</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- General Settings -->
        <div class="modern-card mb-4">
            <div class="modern-card-header"><h5 class="modern-card-title"><i class="fas fa-cog me-2"></i>General Settings</h5></div>
            <div class="modern-card-body">
                <form>
                    <div class="form-group"><label class="form-label">Company Name</label><input type="text" class="form-input" value="CRM Pro"></div>
                    <div class="form-group"><label class="form-label">Default Currency</label>
                        <select class="form-select"><option>USD ($)</option><option>EUR (€)</option><option>GBP (£)</option></select>
                    </div>
                    <div class="form-group"><label class="form-label">Timezone</label>
                        <select class="form-select"><option>America/New_York</option><option>Europe/London</option><option>Asia/Tokyo</option></select>
                    </div>
                    <button type="submit" class="btn-modern btn-modern-primary"><i class="fas fa-save"></i> Save Settings</button>
                </form>
            </div>
        </div>
        
        <!-- Email Settings -->
        <div class="modern-card mb-4">
            <div class="modern-card-header"><h5 class="modern-card-title"><i class="fas fa-envelope me-2"></i>Email Settings</h5></div>
            <div class="modern-card-body">
                <form>
                    <div class="form-group"><label class="form-label">SMTP Host</label><input type="text" class="form-input" placeholder="smtp.gmail.com"></div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label class="form-label">SMTP Port</label><input type="number" class="form-input" value="587"></div>
                        <div class="col-md-6 form-group"><label class="form-label">Encryption</label>
                            <select class="form-select"><option>TLS</option><option>SSL</option></select>
                        </div>
                    </div>
                    <div class="form-group"><label class="form-label">From Email</label><input type="email" class="form-input" placeholder="noreply@yourcompany.com"></div>
                    <button type="submit" class="btn-modern btn-modern-primary"><i class="fas fa-save"></i> Save Email Settings</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- System Info -->
        <div class="modern-card mb-4">
            <div class="modern-card-header"><h5 class="modern-card-title"><i class="fas fa-info-circle me-2"></i>System Info</h5></div>
            <div class="modern-card-body">
                <p><strong>PHP Version:</strong> <?= phpversion() ?></p>
                <p><strong>Database:</strong> Supabase</p>
                <p><strong>Storage:</strong> Cloud</p>
                <hr>
                <a href="#" class="btn-modern btn-modern-outline w-100"><i class="fas fa-database"></i> Backup Database</a>
            </div>
        </div>
    </div>
</div>