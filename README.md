
### Project Overview

This project is a comprehensive restaurant management system designed to enhance both client and administrative experiences in the restaurant industry. It integrates various functionalities to streamline operations, from managing restaurant details and menus to handling reservations and orders.

#### Key Features and Modules

1. **Authentication**
   - The system includes a robust authentication module for clients and employees. New clients register by creating an account using their phone number, ensuring a secure and personalized experience.

2. **Restaurant Management**
   - **User Interface:** Clients can browse through a list of restaurants, search for specific ones, and view essential details such as opening hours and addresses.
   - **Admin Interface:** Administrators have the ability to add new restaurants and update existing restaurant details, ensuring that information is current and accurate.

3. **Menu Management**
   - **Admin Controls:** Administrators can update the menu, including adding new dishes and modifying details of existing ones.
   - **Employee Controls:** Employees responsible for order acceptance can manage dish availability and provide estimated preparation times, optimizing kitchen workflow.

4. **Reservation System**
   - Clients can reserve tables by specifying date and time. The system supports modifications and cancellations of reservations. Initial reservations require phone confirmation, while subsequent reservations are accepted automatically.

5. **Order Management**
   - Clients can place orders from the restaurant's menu, choosing between dining in or picking up the order. Employees manage order statuses, ensuring efficient handling and fulfillment.

6. **Complaint Handling**
   - Clients can submit complaints regarding their experiences by specifying the restaurant and the issue. Administrators can review and respond to these complaints to address and resolve concerns.

7. **Rating System**
   - Clients can rate each dish they’ve consumed on a scale of 1 to 5. The average rating for each dish is displayed in its description, which helps in highlighting the most recommended dishes.

8. **Loyalty Program**
   - A rewards system that gives clients points for every order placed. These points can be redeemed for discounts on future orders, incentivizing repeat business.

9. **Promotions**
   - Administrators can create temporary promotions for specific dishes, modifying their prices and adding a “promo” sticker. Clients can view current promotions and opt to receive notifications about them.

#### Additional Features

- **3D Table Reservation:** An advanced feature allowing clients to select tables from a 3D view of the restaurant, enhancing the reservation experience.
- **Billing Integration:** Integration with Stripe and PayPal facilitates easy and secure payments.
- **QR Menu Management:** A QR code-based menu system simplifies access and ordering, enhancing the customer experience.
- **Notifications and Scheduling:** Mail notifications and calendar planning features help with effective communication and scheduling, keeping clients and staff informed.

### Summary

The project aims to create a seamless and efficient experience for both clients and restaurant administrators. By integrating various functionalities into a unified system, it addresses key aspects of restaurant management, from initial client engagement to ongoing operational tasks. The system not only improves user experience but also streamlines administrative processes, making it a valuable tool for modern restaurants.

Great, let’s break down the project considering the two versions:

### Project Versions

#### 1. **JavaFX Version**

- **Overview:** This version is designed using JavaFX, a framework for building rich desktop applications in Java. It provides a graphical user interface (GUI) for the restaurant management system.
  
- **Key Features:**
  - **Desktop Interface:** Offers a comprehensive desktop application experience for both restaurant staff and administrators.
  - **User and Admin Panels:** Includes modules for authentication, restaurant management, menu updates, reservation handling, order management, complaints, ratings, loyalty programs, and promotions.
  - **3D Reservation System:** Allows clients to view and select tables in a 3D representation of the restaurant.
  - **Integration:** Acts as the core system providing API endpoints that are utilized by the mobile application.
  - **Billing Integration:** Facilitates secure transactions through Stripe and PayPal.
  - **QR Menu Management:** Enables easy menu access through QR codes.
  - **Notifications and Scheduling:** Includes email notifications and calendar integration for effective communication and scheduling.

#### 2. **Symfony Version**

- **Overview:** This version uses Symfony, a popular PHP framework for building web applications. It provides a web-based interface for the restaurant management system.
  
- **Key Features:**
  - **Web Interface:** Offers a web-based interface accessible through browsers, catering to restaurant administrators and possibly staff.
  - **Backend Operations:** Manages core functionalities such as authentication, restaurant details, menu updates, reservations, orders, and more, similar to the JavaFX version.
  - **API Integration:** Provides APIs that can be consumed by external applications, including the mobile app, ensuring that data is consistent across different platforms.

#### 3. **Mobile App in Java**

- **Overview:** This mobile app is built using Java and utilizes the JavaFX version as an API to interact with the core system. It is designed to provide a mobile interface for clients.
  
- **Key Features:**
  - **Mobile Interface:** Delivers a user-friendly interface optimized for mobile devices, allowing clients to interact with the restaurant management system on the go.
  - **Reservation and Order Management:** Clients can make reservations, place orders, view promotions, and manage their loyalty points directly from their mobile devices.
  - **API Consumption:** Utilizes the API provided by the JavaFX version to perform various operations such as accessing restaurant details, managing orders, and handling reservations.
  - **Push Notifications:** Receives notifications about promotions, order status, and reservation updates.

### Integration and Workflow

1. **JavaFX Application:** Acts as the main system providing a robust desktop interface and API endpoints. It handles all the core functionalities and data management.
2. **Symfony Application:** Offers a web-based interface and backend operations, using APIs to ensure consistency with the JavaFX system. It can serve as an alternative access point for administrators.
3. **Mobile App:** Interfaces with the JavaFX system via its API, allowing clients to interact with the system through their smartphones. The app ensures that clients have access to key features such as reservations, orders, and promotions on the go.

### Summary

The project involves three interconnected versions:

- **JavaFX:** The primary desktop application providing a rich, interactive interface and API services.
- **Symfony:** A web-based application offering backend functionality and API access.
- **Mobile App:** A Java-based mobile application that leverages the JavaFX API to deliver essential features to clients on mobile devices.

Each version is designed to complement the others, ensuring a seamless experience across desktop, web, and mobile platforms.
