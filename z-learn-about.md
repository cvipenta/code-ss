**1. Queues (redis or kafka)**
 * Read about `event driven architecture`
 * AWS event driven arch

[https://aws.amazon.com/event-driven-architecture/](https://aws.amazon.com/event-driven-architecture/)
Amazon Simple Notification Service

**2. LOCK and MUTEX**
 * read about lock types (lock, mutex, semaphore)

[https://laravel.com/docs/9.x/cache#atomic-locks](https://laravel.com/docs/9.x/cache#atomic-locks)
[https://symfony.com/doc/current/components/lock.html](https://symfony.com/doc/current/components/lock.html)

[https://symfony.com/doc/current/components/lock.html](https://symfony.com/doc/current/components/lock.html)

**3. Data Dog or similar tools (Kibana or NewRelic)**
[https://www.datadoghq.com/product/error-tracking/](https://www.datadoghq.com/product/error-tracking/)

**4. OWASP Top 10 2021**  
Top 10: 
> **A01 Broken Access Control** (https://owasp.org/Top10/A01_2021-Broken_Access_Control/)  
    CWE-200: Exposure of Sensitive Information to an Unauthorized Actor  
    CWE-201: Exposure of Sensitive Information Through Sent Data  
    CWE-352: Cross-Site Request Forgery  

> **A02 Cryptographic Failures** (Sensitive Data Exposure)

> **A03 Injection**  
    CWE-79: Cross-site Scripting  
    CWE-89: SQL Injection  
    CWE-73: External Control of File Name or Path

> **A04 Insecure Design**

> **A05 Security Misconfiguration**

> **A06 Vulnerable and Outdated Components**

> **A07 Identification and Authentication Failures** (Broken Authentication)  
    CWE-297: Improper Validation of Certificate with Host Mismatch  
    CWE-287: Improper Authentication  
    CWE-384: Session Fixation  

> **A08 Software and Data Integrity Failures**

> **A09 Security Logging and Monitoring Failures**  
    CWE-778 Insufficient Logging  
    CWE-117 Improper Output Neutralization for Logs  
    CWE-223 Omission of Security-relevant Information  
    CWE-532 Insertion of Sensitive Information into Log File

> **A10 Server Side Request Forgery (SSRF)**


\
Next:
   - Code Quality issues
   - Denial of Service
   - Memory Management Errors 

**OWASP scanning tools**:
* https://www.zaproxy.org/

\
**5. How `https` works**
[https://www.thesslstore.com/blog/how-does-https-work/](https://www.thesslstore.com/blog/how-does-https-work/)

**6. SOLID**
    https://laracasts.com/series/solid-principles-in-php

SOLID in simple words:

> **S** A class should have one and only one reason to change, meaning that a class should have only one job.  
    Write classes so that your code "fits in your head".

> **O** Objects or entities should be open for extension but closed for modification.  
> Design your classes so that you can change their behavior without changing their code.

> **L** Every subclass or derived class should be substitutable for their base or parent class.
> If a class extends a base class or implements an interface, make your class behave like it is supposed to.

> **I** A client should never be forced to implement an interface that it doesn’t use, or clients shouldn’t be forced to depend on methods they do not use.  
> If a class has a large interface - so a lot of methods - and you often inject the class and only use some of these methods - consider splitting your class into smaller pieces.

> **D** 1. High level modules should not depend on low level modules, both should depend on abstractions - for example, interfaces. 2. Abstractions should not depend on details. Details - meaning concrete implementations - should depend on abstractions.
> - Classes should depend on interfaces instead of concrete classes.
> - Those interfaces should be designed by the class that uses them, not by the classes that will implement them.     
> Prefer type-hinting interfaces and allow each interface to be designed for the "high level" class that will use it, instead of for the low-level class that will implement it.

**7. DESIGN PATTERNS**

**creational**: factory method, singleton, prototype, builder  
**structural**: decorator, adapter, facade, bridge  
**behavioral**: observer, strategy, template, Chain of responsibility, command  


https://laracasts.com/series/design-patterns-in-php

**8. Php library `Money`**
Documentation: https://www.moneyphp.org/en/stable/
    
`composer require moneyphp/money`

**9. REST verbs**
https://medium.com/@pawanpiumal1/common-http-verbs-in-restful-web-services-b517c90637bb

**10. Laravel service provider**
- AppServiceProvider
- read about differences between boot() and register()
  - The **register()** method is where you should add bindings to the service container. 
  - The **boot()** method is for performing actions after all service providers have registered their services.

Best answer: https://laracasts.com/discuss/channels/general-discussion/difference-between-boot-and-register-method?reply=4507

**11. Unit tests - dataProvider**

**12. Laravel Middleware in general**
https://laravel.com/docs/9.x/middleware
