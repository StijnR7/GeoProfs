describe('Leave request flow via dashboard', () => {

  it('User can submit leave request and see it in behandeling', () => {
    // Login
    cy.visit('/login')
    cy.get('input[name=email]').type('test@example.com')
    cy.get('input[name=password]').type('password')
    cy.get('button[type=submit]').click()
    cy.url().should('include', '/dashboard')

    // Vul start- en einddatum in
    cy.get('#leave_start').clear().type('2026-01-29')
    cy.get('#leave_end').clear().type('2026-01-30')

    // Kies type
    cy.get('select[name=type]').select('verlof')

    // Vul reden
    cy.get('textarea[name=reason]').type('Test reden voor aanvraag')

    // Klik op submit
    cy.get('#submit-btn').click()

    // Ga naar "Mijn aanvragen"
    cy.get('.nav-btn').contains('Mijn Aanvragen').click()


    // Controleer status
    cy.contains('In behandeling').should('exist')
  })

})
