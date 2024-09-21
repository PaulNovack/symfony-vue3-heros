
describe('Application Test', () => {
  before(() => {
    // Set up the session with cy.session()
    cy.session('user-session', () => {
      // Log in or perform any other setup steps that need to be persistent
      cy.visit('/');
    });

  });


  it('should load the home page', () => {
    // Visit the home page using the relative path
    cy.visit('/');

    // Assert that the home page contains the expected content
    cy.contains('Marvel Heroes'); // Change this to match a unique element/text on your home page
  });

  it('should navigate to the Hero Listing page', () => {
    // Visit the Hero Listing page using the relative path
    cy.visit('/heros'); // Adjust to '/heroes' if necessary

    // Assert that the Hero Listing page contains the expected content
    cy.contains('Hero Listing'); // Change this to match a unique element/text on the hero listing page
  });

  it('should navigate to the About page', () => {
    // Visit the About page using the relative path
    cy.visit('/about');

    // Assert that the About page contains the expected content
    cy.contains('About'); // Change this to match a unique element/text on the about page
  });

  it('navigate to hero page and search for gggg and get no results', () => {
    // Visit the About page using the relative path
    cy.visit('/heros');

    cy.get('#search').type('gggg')
    cy.contains('No heroes found')
  });

  it('navigate to hero page and search for hulk and get results', () => {
    // Visit the About page using the relative path
    cy.visit('/heros');

    cy.get('#search').type('hulk')
    cy.contains('No heroes found').should('not.exist');
    cy.get('#hero-div-1');
  });
  it('navigate to hero page and add a favorite and check it exists', () => {
    cy.visit('/heros');
    cy.get('#search').type('spider')
    cy.get('#new-favorite-input').type('My Spider List')
    cy.get('#add-new-favorite-button').click()
    cy.get('#favorite-list').click()
    cy.contains('My Spider List')
  });
  it('navigate to hero page add 2 favorites and then delete one verify does not exist', () => {
    cy.visit('/heros');
    cy.get('#search').type('spider')
    cy.get('#new-favorite-input').type('My Spider List')
    cy.get('#add-new-favorite-button').click()
    cy.get('#search').type('hulk')
    cy.get('#new-favorite-input').type('My Hulk List')
    cy.get('#add-new-favorite-button').click()
    cy.get('#favorite-list').click()
    cy.contains('My Spider List')
    cy.get('#trash-0').click()
    cy.contains('My Spider List').should('not.exist')
    cy.contains('My Hulk List')
  });
  it('navigate to hero page add 2 favorites and\n'
  + ' then delete one\n'
  + ' verify one deleted does exist', () => {
    cy.visit('/heros');
    cy.get('#search').type('spider')
    cy.get('#new-favorite-input').type('My Spider List')
    cy.get('#add-new-favorite-button').click()
    cy.get('#search').type('hulk')
    cy.get('#new-favorite-input').type('My Hulk List')
    cy.get('#add-new-favorite-button').click()
    cy.get('#favorite-list').click()
    cy.contains('My Spider List')
    cy.get('#trash-0').click()
    cy.contains('My Hulk List')
  });

  it('navigate to hero page add a favorites list and add\n'
      + '3 heroes to list'
      + ' and check list has 3 heros when\n navigated to\n'
      + 'then delete all favorites and see delete works by\n'
      + 'by reselecting list', () => {
    cy.visit('/heros');

    cy.get('#new-favorite-input').type('My Spider List')
    cy.get('#add-new-favorite-button').click()
    cy.get('#favorite-list').click();
    cy.get('ul').should('be.visible');
    cy.get('ul li:first-child span').click();
    cy.get('#search').clear();
    cy.get('#search').type('spider');
    cy.get('#hero-checkbox-0').check();
    cy.get('#hero-checkbox-1').check();
    cy.get('#hero-checkbox-2').check();
    cy.get('#add-heroes-button').click();
    cy.get('#favorite-list').click();
    cy.get('ul').should('be.visible');
    cy.get('ul li:first-child span').click();
    cy.get('#hero-parent-div').children().should('have.length', 3, { timeout: 10000 });
    cy.get('#hero-remove-atag-0').click()
    cy.get('#hero-remove-atag-0').click()
    cy.get('#hero-remove-atag-0').click()
    cy.contains('No heroes found')
    cy.get('#search').type('dog')
    cy.get('#favorite-list').click();
    cy.contains('No heroes found').should("not.exist")
    cy.contains('1 heroes found').should("exist")
    cy.get('ul').should('be.visible');
    cy.get('ul li:first-child span').click();
    cy.contains('No heroes found').should("exist")

  });
});
