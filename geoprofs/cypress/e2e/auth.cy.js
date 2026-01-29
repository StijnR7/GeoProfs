describe('Authentication flow', () => {

  it('User can log in with correct credentials', () => {
    cy.visit('/login')

    cy.get('input[name=email]')
      .type('test@example.com')

    cy.get('input[name=password]')
      .type('password')

    cy.get('button[type=submit]')
      .click()

    cy.url().should('include', '/dashboard')
  })

  it('User cannot log in with wrong credentials', () => {
    cy.visit('/login')

    cy.get('input[name=email]')
      .type('wrong@example.com')

    cy.get('input[name=password]')
      .type('wrong')

    cy.get('button[type=submit]')
      .click()

    cy.url().should('include', '/login')
    cy.contains('De opgegeven inloggegevens zijn niet correct.')
  })

it('User can log out via dashboard button', () => {
  cy.visit('/login')

  cy.get('input[name=email]').type('test@example.com')
  cy.get('input[name=password]').type('password')
  cy.get('button[type=submit]').click()

  cy.url().should('include', '/dashboard')

  cy.contains('Uitloggen').click() // of: cy.get('[data-cy=logout]').click() als je een data-cy gebruikt

  cy.url().should('eq', `${Cypress.config().baseUrl}/`)
})




})
