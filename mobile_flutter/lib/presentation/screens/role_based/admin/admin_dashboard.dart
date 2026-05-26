import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../providers/user_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';
import '../../../widgets/common/custom_chart.dart';

class AdminDashboard extends ConsumerStatefulWidget {
  const AdminDashboard({super.key});

  @override
  ConsumerState<AdminDashboard> createState() => _AdminDashboardState();
}

class _AdminDashboardState extends ConsumerState<AdminDashboard> {
  @override
  void initState() {
    super.initState();
    ref.read(userProvider.notifier).loadUsers();
  }

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(authProvider).user;
    final userState = ref.watch(userProvider);
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Admin Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.person_add),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.settings),
            onPressed: () {},
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => ref.read(userProvider.notifier).loadUsers(),
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
                        user?.initials ?? 'AD',
                        style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFF1A56DB)),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Welcome, ${user?.name ?? 'Admin'}',
                            style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            RoleConstants.roleDisplayNames[RoleConstants.admin] ?? 'Administrator',
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
                'System Statistics',
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
                    value: '${userState.users.length}',
                    icon: Icons.people,
                    color: Colors.blue,
                  ),
                  StatisticCard(
                    title: 'Active Users',
                    value: '${userState.users.where((u) => u.status == 'active').length}',
                    icon: Icons.person,
                    color: Colors.green,
                  ),
                  StatisticCard(
                    title: 'Total Roles',
                    value: '10',
                    icon: Icons.admin_panel_settings,
                    color: Colors.orange,
                  ),
                  StatisticCard(
                    title: 'System Health',
                    value: '98%',
                    icon: Icons.health_and_safety,
                    color: Colors.purple,
                  ),
                ],
              ),
              const SizedBox(height: 24),
              const Text(
                'Recent Users',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              ListView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: userState.users.take(5).length,
                itemBuilder: (context, index) {
                  final u = userState.users[index];
                  return ListTile(
                    leading: CircleAvatar(
                      child: Text(u.initials),
                    ),
                    title: Text(u.name ?? 'Unknown'),
                    subtitle: Text(u.email ?? ''),
                    trailing: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: u.isActive ? Colors.green.withOpacity(0.2) : Colors.grey.withOpacity(0.2),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Text(
                        u.status ?? 'inactive',
                        style: TextStyle(
                          color: u.isActive ? Colors.green : Colors.grey,
                          fontSize: 12,
                        ),
                      ),
                    ),
                    onTap: () {},
                  );
                },
              ),
            ],
          ),
        ),
      ),
    );
  }
}