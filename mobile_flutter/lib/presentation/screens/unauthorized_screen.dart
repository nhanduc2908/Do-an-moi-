import 'package:flutter/material.dart';
import '../../core/routes/route_names.dart';

class UnauthorizedScreen extends StatelessWidget {
  const UnauthorizedScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(24.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.block, size: 100, color: Colors.red),
              const SizedBox(height: 20),
              const Text(
                'Access Denied',
                style: TextStyle(fontSize: 28, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 10),
              const Text(
                'You do not have permission to access this page.',
                textAlign: TextAlign.center,
                style: TextStyle(fontSize: 16, color: Colors.grey),
              ),
              const SizedBox(height: 40),
              ElevatedButton(
                onPressed: () => context.go(RouteNames.login),
                child: const Text('Back to Login'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}