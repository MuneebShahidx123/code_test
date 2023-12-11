Note:i've only refactor those function which were being used in the repository.

# Code Review Feedback
1. **Avoid Direct Access to Env Variables:**
   - You should never access env variables directly. Once you cache your config, all env calls return null. Try running this and see your app fail.
     ```bash
     php artisan config:cache
     ```
   -  env('ADMIN_ROLE_ID') is not good approach . Instead, get it from the config file.

2. **Minimize If-Else Chains:**
   - In BookingRepository Store function you were using two many if else statements. Consider using a switch statement instead of excessive if-else chains for improved readability and maintainability.

3. **Utilize Request Classes:**
   - Where applicable, make use of Laravel's Request classes to encapsulate and handle incoming HTTP requests more effectively.

4. **Code Reusability:**
   - Reuse code to avoid redundancy. This helps in maintaining a cleaner and more scalable codebase. Your codebase has a very high redundancy code.

5. **Consider MVSR Pattern:**
   - If possible, try to use the Model-View-Service-Repository (MVSR) pattern for better organization and separation of concerns. Currently, you are writing business logic inside the repository, which is not a good approach.
   - Maintain consistency by avoiding pasting code directly inside controllers if it's intended for the repository. Keep the responsibilities clear and distinct. Your `distance_feed` function had this problem.

6. **New Constructor Property Syntax:**
   - The new constructor property syntax allows you to define and initialize properties in a more concise manner.

7. **Use PSR Coding Standards:**
   - Use PSR coding standards for better code readability and compatibility with the PHP ecosystem.

8. **Pay Attention to Spelling Mistakes:**
   - Pay attention to spelling mistakes in variable names, comments to ensure professionalism and clarity.

9. **Replace old-school array syntax with the shorthand `[]` for improved code aesthetics.**

10. **Clearly Define Return Types for functions/methods.**

11. **Single Responsibility Principle (SRP):**
   - Ensure that each class/method adheres to the SRP, focusing on a single responsibility.

12. **Don't Repeat Yourself (DRY) Principle:**
   - Eliminate redundancy by applying the DRY principle.

13. **Solid Principles:**
   - Try to follow SOLID principles to create a more maintainable and scalable codebase.

14. **Missing status code:**
    ```php
    // Incorrect: Missing status code
    return response(['error' => 'SMS not sent']);

    // Correct: Include the appropriate status code
    return response(['error' => 'SMS not sent'], 500);
    ```

15. **Use Early Returns:**
   - Benefits of using early returns:
     - Clarity: Each early return handles a specific condition, making the code logic more apparent.
     - Reduced Nesting: Early returns help avoid deep nesting of conditions, making the code flatter and easier to follow.
     - Easier Maintenance: Changes or additions to the code are often more straightforward when using early returns, as you can focus on specific conditions independently.

16. **Break Down Large Functions:**
   - Breaking down large functions into smaller, more focused functions not only enhances code readability but also makes it easier to write unit tests for each individual component.

17. **Validate Function Usage:**
    - `request->validate([rules...])` returns only validated data if the validation passes, but `request->all()` returns all data. So, use the `validated` function where possible.

18. **Meaningful Variable and Function Names:**
    - Variables names and functions names should always be meaningful.

19. **Email and notifications:**
    - use event listeners when sending notifications.
    - use should queue or atleast try catch block when sending email.

20. **Low Coupling and High Cohesion:**
    - Modularize the system into smaller, manageable components.
    - Apply events/listeners for decoupled communication.
    - Depend on abstractions (Dependency Inversion Principle, DIP) for flexibility.

21. **Query Optimize:**
    - using ->get()->first() is very bad approach it will take everything from db and then pick first from it. so use directly first() if you want.
    - same you can use directly pluck instead converting it to collect and then using pluck.
    - Do not repeat your queries if you already have the data.


22. **Laravel Functionality:**
    - Use Response classes and other laravel functions as much as possible.


# How to run test cases

1. **For  install:**
     ```bash
     cd tests
     composer require --dev phpunit/phpunit ^10 
    ```
2. **To  Run:**
     ```bash
     ./vendor/bin/phpunit app
     ```
     OR
     ```bash
     ./vendor/bin/phpunit --testdox app
     ```
# Test Case report for WillExpireAtTest

1. **WillExpireAtTest::testWillExpireAtWhenDifferenceIsLessOrEqualTo24**
   - Failed asserting that two strings are equal.
   - Expected: '2023-12-09 15:39:23'
   - Actual: '2023-12-10 14:09:23'
   - File and line where the failure occurred: `tests\app\WillExpireAtTest.php:26`

2. **WillExpireAtTest::testWillExpireAtWhenDifferenceIsGreaterThan24AndLessOrEqualTo72**
   - Failed asserting that two strings are equal.
   - Expected: '2023-12-10 06:09:23'
   - Actual: '2023-12-12 14:09:23'
   - File and line where the failure occurred: `tests\app\WillExpireAtTest.php:33`

3. **WillExpireAtTest::testWillExpireAtWhenDifferenceIsGreaterThan72**
   - Failed asserting that two strings are equal.
   - Expected: '2023-12-10 15:09:23'
   - Actual: '2023-12-12 15:09:23'
   - File and line where the failure occurred: `tests\app\WillExpireAtTest.php:40`

**Summary:**
- Total Tests: 4
- Assertions: 4
- Failures: 3
- Warnings: 1

i am not able to run the second test case that requires to start the app
