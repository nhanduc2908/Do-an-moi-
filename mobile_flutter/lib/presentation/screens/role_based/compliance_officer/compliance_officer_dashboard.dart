import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';

class ComplianceOfficerDashboard extends ConsumerWidget {
  const ComplianceOfficerDashboard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final user = ref.watch(authProvider).user;
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Compliance Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF2ECC71), Color(0xFF27AE60)],
                ),
                borderRadius: BorderRadius.circular(16),
              ),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 30,
                    backgroundColor: Colors.white,
                    child: Text(
                      user?.initials ?? 'CO',
                      style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFF2ECC71)),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Welcome, ${user?.name ?? 'Compliance Officer'}',
                          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          RoleConstants.roleDisplayNames[RoleConstants.complianceOfficer] ?? 'Compliance Officer',
                          style: const TextStyle(color: Colors.white70),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'Compliance Status',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            GridView.count(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              crossAxisCount: 2,
              mainAxisSpacing: 16,
              crossAxisSpacing: 16,
              childAspectRatio: 1.5,
              children: [
                StatisticCard(
                  title: 'ISO 27001',
                  value: '85%',
                  icon: Icons.check_circle,
                  color: Colors.green,
                ),
                StatisticCard(
                  title: 'GDPR',
                  value: '78%',
                  icon: Icons.security,
                  color: Colors.blue,
                ),
                StatisticCard(
                  title: 'PCI DSS',
                  value: '72%',
                  icon: Icons.credit_card,
                  color: Colors.orange,
                ),
                StatisticCard(
                  title: 'HIPAA',
                  value: '90%',
                  icon: Icons.health_and_safety,
                  color: Colors.purple,
                ),
              ],
            ),
            const SizedBox(height: 24),
            const Text(
              'Upcoming Audits',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            Card(
              child: ListTile(
                leading: const Icon(Icons.audiotrack, color: Colors.blue),
                title: const Text('ISO 27001 Internal Audit'),
                subtitle: const Text('Due: June 15, 2024'),
                trailing: const Chip(label: Text('Pending'), backgroundColor: Colors.orange),
                onTap: () {},
              ),
            ),
            Card(
              child: ListTile(
                leading: const Icon(Icons.audiotrack, color: Colors.red),
                title: const Text('GDPR Compliance Check'),
                subtitle: const Text('Due: July 1, 2024'),
                trailing: const Chip(label: Text('Scheduled'), backgroundColor: Colors.green),
                onTap: () {},
              ),
            ),
            Card(
              child: ListTile(
                leading: const Icon(Icons.audiotrack, color: Colors.purple),
                title: const Text('PCI DSS Assessment'),
                subtitle: const Text('Due: August 20, 2024'),
                trailing: const Chip(label: Text('Planning'), backgroundColor: Colors.grey),
                onTap: () {},
              ),
            ),
          ],
        ),
      ),
    );
  }
}