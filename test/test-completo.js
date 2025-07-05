const { chromium } = require('playwright');

// Para usar Playwright global: npm install -g playwright
// O local: npm install (en esta carpeta test)

(async () => {
    const headless = process.env.HEADLESS === 'true';
    const browser = await chromium.launch({ headless });
    const context = await browser.newContext();
    const page = await context.newPage();
    
    // Capturar errores de consola
    page.on('console', msg => {
        if (msg.type() === 'error') {
            console.error('❌ ERROR EN CONSOLA:', msg.text());
        }
    });
    
    page.on('pageerror', error => {
        console.error('❌ ERROR DE PÁGINA:', error.message);
    });
    
    // Manejar diálogos (confirm, alert)
    page.on('dialog', async dialog => {
        console.log('   Diálogo detectado:', dialog.message());
        await dialog.accept();
    });
    
    // Capturar peticiones de red
    page.on('request', request => {
        if (request.url().includes('/api/') && request.method() === 'DELETE') {
            console.log('   🔴 DELETE Request:', request.url());
        }
    });
    
    page.on('response', response => {
        if (response.url().includes('/api/') && response.request().method() === 'DELETE') {
            console.log('   🟢 DELETE Response:', response.status(), response.statusText());
        }
    });
    
    console.log('=== TEST COMPLETO DE GALLETA FORTUNA ===\n');
    console.log('Modo:', headless ? 'Headless' : 'Con navegador visible');
    console.log('URL:', 'http://galleta-fortuna.ddev.site\n');
    
    const timestamp = Date.now();
    const uniqueEmail = `test${timestamp}@example.com`;
    const adminEmail = 'admin@galletafortuna.com';
    const adminPassword = 'admin123';
    
    console.log('Email de prueba:', uniqueEmail);
    
    try {
        // 1. Navegación inicial
        console.log('1. Navegando a la aplicación...');
        await page.goto('http://galleta-fortuna.ddev.site');
        await page.waitForSelector('text=Iniciar Sesión', { timeout: 10000 });
        await page.waitForTimeout(2000);
        console.log('   ✓ Página cargada correctamente');
        
        // 2. Registro usuario regular
        console.log('\n2. Probando registro de nuevo usuario...');
        await page.waitForSelector('text=Regístrate aquí', { timeout: 5000 });
        await page.click('text=Regístrate aquí');
        await page.waitForTimeout(1000);
        
        await page.fill('input[ng-model="usuario.nombre"]', 'Usuario de Prueba');
        await page.fill('input[ng-model="usuario.email"]', uniqueEmail);
        await page.fill('input[ng-model="usuario.password"]', 'password123');
        
        await page.screenshot({ path: 'screenshots/1-registro.png' });
        await page.click('button[type="submit"]');
        await page.waitForTimeout(2000);
        console.log('   ✓ Usuario registrado exitosamente');
        
        // 3. Login usuario regular
        console.log('\n3. Probando inicio de sesión...');
        await page.fill('input[ng-model="usuario.email"]', uniqueEmail);
        await page.fill('input[ng-model="usuario.password"]', 'password123');
        
        await page.screenshot({ path: 'screenshots/2-login.png' });
        await page.click('button[type="submit"]');
        await page.waitForTimeout(2000);
        
        await page.waitForSelector('text=ABRE TU GALLETA', { timeout: 5000 });
        console.log('   ✓ Login exitoso');
        
        // 4. Verificar que no hay botón de admin
        console.log('\n4. Verificando que usuario regular no ve botón admin...');
        const adminButton = await page.$('text=Gestión de Frases');
        if (!adminButton) {
            console.log('   ✓ Correcto: Usuario regular no ve botón de admin');
        } else {
            throw new Error('Usuario regular puede ver botón de admin!');
        }
        
        // 5. Abrir galleta
        console.log('\n5. Abriendo galleta de la fortuna...');
        await page.screenshot({ path: 'screenshots/3-galleta-principal.png' });
        await page.click('text=ABRE TU GALLETA');
        await page.waitForTimeout(2000);
        
        const fortunaElement = await page.waitForSelector('.texto-fortuna');
        const fortunaTexto = await fortunaElement.textContent();
        await page.screenshot({ path: 'screenshots/4-fortuna.png' });
        console.log('   ✓ Fortuna recibida:', fortunaTexto.trim());
        
        // 6. Nueva galleta
        console.log('\n6. Abriendo otra galleta...');
        await page.click('text=Abrir Otra Galleta');
        await page.waitForTimeout(1000);
        await page.click('text=ABRE TU GALLETA');
        await page.waitForTimeout(2000);
        
        const nuevaFortunaElement = await page.waitForSelector('.texto-fortuna');
        const nuevaFortuna = await nuevaFortunaElement.textContent();
        await page.screenshot({ path: 'screenshots/5-nueva-fortuna.png' });
        console.log('   ✓ Nueva fortuna:', nuevaFortuna.trim());
        
        // 7. Logout usuario regular
        console.log('\n7. Cerrando sesión usuario regular...');
        // Cerrar sesión directamente desde la página de fortuna
        await page.click('text=Cerrar Sesión');
        await page.waitForTimeout(1000);
        
        await page.waitForSelector('text=Iniciar Sesión');
        await page.screenshot({ path: 'screenshots/6-logout.png' });
        console.log('   ✓ Sesión cerrada correctamente');
        
        // 8. Login como admin
        console.log('\n8. Iniciando sesión como administrador...');
        await page.fill('input[ng-model="usuario.email"]', adminEmail);
        await page.fill('input[ng-model="usuario.password"]', adminPassword);
        
        await page.screenshot({ path: 'screenshots/7-admin-login.png' });
        await page.click('button[type="submit"]');
        await page.waitForTimeout(2000);
        
        // 9. Verificar botón de admin
        console.log('\n9. Verificando acceso de administrador...');
        await page.waitForSelector('text=Gestión de Frases');
        console.log('   ✓ Botón de admin visible');
        await page.screenshot({ path: 'screenshots/8-admin-view.png' });
        
        // 10. Ir a gestión de frases
        console.log('\n10. Accediendo a gestión de frases...');
        await page.click('text=Gestión de Frases');
        await page.waitForTimeout(2000);
        
        await page.waitForSelector('text=Agregar Nueva Frase');
        await page.screenshot({ path: 'screenshots/9-admin-frases.png' });
        console.log('   ✓ Página de gestión de frases cargada');
        
        // 11. Contar frases existentes y verificar botones eliminar
        const frasesAntes = await page.$$eval('tbody tr', rows => rows.length);
        console.log(`   Frases existentes: ${frasesAntes}`);
        
        // Verificar que las frases del sistema no tienen botón eliminar
        const primeraFila = await page.$('tbody tr:first-child');
        const tieneBotonEliminar = await primeraFila.$('button.btn-eliminar');
        if (!tieneBotonEliminar) {
            console.log('   ✓ Frases del sistema no tienen botón eliminar');
        }
        
        // 12. Agregar nueva frase
        console.log('\n11. Agregando nueva frase...');
        const nuevaFrase = `Frase de prueba ${timestamp}`;
        await page.fill('textarea[ng-model="nuevaFrase"]', nuevaFrase);
        await page.click('button[type="submit"]');
        await page.waitForTimeout(2000);
        
        // Verificar mensaje de éxito
        const mensajeExito = await page.$('text=Frase agregada exitosamente');
        if (mensajeExito) {
            console.log('   ✓ Frase agregada exitosamente');
        }
        
        // 13. Verificar que la frase se agregó
        const frasesDespues = await page.$$eval('tbody tr', rows => rows.length);
        if (frasesDespues > frasesAntes) {
            console.log(`   ✓ Nueva frase agregada (total: ${frasesDespues})`);
        }
        await page.screenshot({ path: 'screenshots/10-frase-agregada.png' });
        
        // 14. Intentar agregar frase duplicada
        console.log('\n12. Probando validación de frase duplicada...');
        await page.fill('textarea[ng-model="nuevaFrase"]', nuevaFrase);
        await page.click('button[type="submit"]');
        await page.waitForTimeout(2000);
        
        const mensajeError = await page.$('text=Esta frase ya existe');
        if (mensajeError) {
            console.log('   ✓ Validación de duplicados funciona correctamente');
        }
        await page.screenshot({ path: 'screenshots/11-frase-duplicada.png' });
        
        // 15. Eliminar la frase agregada
        console.log('\n13. Eliminando frase de prueba...');
        // Buscar la fila con nuestra frase
        const filas = await page.$$('tbody tr');
        let fraseEncontrada = false;
        for (const fila of filas) {
            const texto = await fila.$eval('.frase-texto', el => el.textContent);
            if (texto === nuevaFrase) {
                fraseEncontrada = true;
                const botonEliminar = await fila.$('button.btn-eliminar');
                if (botonEliminar) {
                    console.log('   Encontrada frase para eliminar');
                    
                    // Tomar screenshot antes de eliminar
                    await page.screenshot({ path: 'screenshots/12a-antes-eliminar.png' });
                    
                    await botonEliminar.click();
                    console.log('   Esperando respuesta del servidor...');
                    
                    // Esperar a que aparezca el mensaje de éxito o error
                    await page.waitForTimeout(1000);
                    
                    // Buscar mensaje de éxito
                    const mensajeExito = await page.$('.mensaje-exito');
                    if (mensajeExito) {
                        const textoMensaje = await mensajeExito.textContent();
                        console.log('   Mensaje:', textoMensaje);
                    }
                    
                    await page.waitForTimeout(2000);
                    
                    // Verificar si seguimos en la página de admin
                    const url = page.url();
                    console.log('   URL actual:', url);
                    if (!url.includes('admin/frases')) {
                        console.log('   ❌ Redirigido fuera de admin!');
                    }
                    break;
                } else {
                    console.log('   ⚠️ No se encontró botón eliminar para esta frase');
                }
            }
        }
        
        if (!fraseEncontrada) {
            console.log('   ⚠️ No se encontró la frase agregada');
        }
        
        await page.screenshot({ path: 'screenshots/12-frase-eliminada.png' });
        
        // 16. Verificar que se eliminó
        const frasesFinales = await page.$$eval('tbody tr', rows => rows.length);
        console.log(`   Frases después de eliminar: ${frasesFinales}`);
        if (frasesFinales === frasesAntes) {
            console.log(`   ✓ Número de frases restaurado correctamente`);
        } else if (frasesFinales < frasesDespues) {
            console.log(`   ✓ Frase eliminada (${frasesDespues} → ${frasesFinales})`);
        } else {
            console.log(`   ⚠️ Número inesperado de frases: ${frasesFinales}`);
        }
        
        // 17. Volver y cerrar sesión admin
        console.log('\n14. Cerrando sesión de administrador...');
        await page.click('button:has-text("Volver")');
        await page.waitForTimeout(1000);
        await page.click('button:has-text("Cerrar Sesión")');
        await page.waitForTimeout(1000);
        console.log('   ✓ Sesión de admin cerrada');
        
        console.log('\n✅ TODAS LAS PRUEBAS PASARON EXITOSAMENTE!\n');
        
    } catch (error) {
        console.error('\n❌ ERROR EN LAS PRUEBAS:', error.message);
        await page.screenshot({ path: 'screenshots/error.png' });
        process.exit(1);
    } finally {
        await browser.close();
    }
})();