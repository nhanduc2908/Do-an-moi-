import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';
import '../../../widgets/dashboard/quick_action_card.dart';

class SuperAdminDashboard extends ConsumerStatefulWidget {
  const SuperAdminDashboard({super.key});

  @override
  ConsumerState<SuperAdminDashboard> createState() => _SuperAdminDashboardState();
}

class _SuperAdminDashboardState extends ConsumerState<SuperAdminDashboard> {
  @override
  Widget build(BuildContext context) {
    final user = ref.watch(authProvider).user;
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Super Admin Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.person),
            onPressed: () {},
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {},
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFF1A56DB), Color(0xFF1E429F)],
                  ),
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Row(
                  children: [
                    CircleAvatar(
                      radius: 30,
                      backgroundColor: Colors.white,
                      child: Text(
                        user?.initials ?? 'SA',
                        style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFF1A56DB)),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Welcome, ${user?.name ?? 'Super Admin'}',
                            style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            RoleConstants.roleDisplayNames[RoleConstants.superAdmin] ?? 'Super Administrator',
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
                'System Overview',
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
                    title: 'Total Users',
                    value: '156',
                    icon: Icons.people,
                    color: Colors.blue,
                  ),
                  StatisticCard(
                    title: 'Active Sessions',
                    value: '42',
                    icon: Icons.devices,
                    color: Colors.green,
                  ),
                  StatisticCard(
                    title: 'System Health',
                    value: '98%',
                    icon: Icons.health_and_safety,
                    color: Colors.orange,
                  ),
                  StatisticCard(
                    title: 'Pending Updates',
                    value: '3',
                    icon: Icons.update,
                    color: Colors.red,
                  ),
                ],
              ),
              const SizedBox(height: 24),
              const Text(
                'Quick Actions',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              Wrap(
                spacing: 12,
                runSpacing: 12,
                children: [
                  QuickActionCard(
                    title: 'System Monitor',
                    icon: Icons.monitor,
                    onTap: () {},
                  ),
                  QuickActionCard(
                    title: 'Audit Trail',
                    icon: Icons.history,
                    onTap: () {},
                  ),
                  QuickActionCard(
                    title: 'Master Control',
                    icon: Icons.admin_panel_settings,
                    onTap: () {},
                  ),
                  QuickActionCard(
                    title: 'Backup',
                    icon: Icons.backup,
                    onTap: () {},
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}