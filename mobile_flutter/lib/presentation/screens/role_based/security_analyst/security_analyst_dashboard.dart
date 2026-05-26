import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../providers/vulnerability_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';

class SecurityAnalystDashboard extends ConsumerStatefulWidget {
  const SecurityAnalystDashboard({super.key});

  @override
  ConsumerState<SecurityAnalystDashboard> createState() => _SecurityAnalystDashboardState();
}

class _SecurityAnalystDashboardState extends ConsumerState<SecurityAnalystDashboard> {
  @override
  void initState() {
    super.initState();
    ref.read(vulnerabilityProvider.notifier).loadVulnerabilities();
    ref.read(vulnerabilityProvider.notifier).loadStats();
  }

  @override
  Widget build(BuildContext context) {
    final user = ref.watch(authProvider).user;
    final vulnState = ref.watch(vulnerabilityProvider);
    
    final criticalCount = vulnState.vulnerabilities.where((v) => v.severity == 'CRITICAL').length;
    final highCount = vulnState.vulnerabilities.where((v) => v.severity == 'HIGH').length;
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Security Analysis Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(vulnerabilityProvider.notifier).loadVulnerabilities(),
          ),
          IconButton(
            icon: const Icon(Icons.search),
            onPressed: () {},
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () => ref.read(vulnerabilityProvider.notifier).loadVulnerabilities(),
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: const EdgeInsets.all(20),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFF3498DB), Color(0xFF2980B9)],
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
                        style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFF3498DB)),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Welcome, ${user?.name ?? 'Security Analyst'}',
                            style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                          ),
                          const SizedBox(height: 4),
                          Text(
                            RoleConstants.roleDisplayNames[RoleConstants.securityAnalyst] ?? 'Security Analyst',
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
                'Threat Overview',
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
                    title: 'Critical Vulnerabilities',
                    value: '$criticalCount',
                    icon: Icons.error,
                    color: Colors.red,
                  ),
                  StatisticCard(
                    title: 'High Vulnerabilities',
                    value: '$highCount',
                    icon: Icons.warning,
                    color: Colors.orange,
                  ),
                  StatisticCard(
                    title: 'Total Alerts',
                    value: '24',
                    icon: Icons.notifications,
                    color: Colors.blue,
                  ),
                  StatisticCard(
                    title: 'False Positives',
                    value: '8',
                    icon: Icons.fact_check,
                    color: Colors.purple,
                  ),
                ],
              ),
              const SizedBox(height: 24),
              const Text(
                'Recent Alerts',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              ..._getRecentAlerts().map((alert) => Card(
                margin: const EdgeInsets.only(bottom: 12),
                child: ListTile(
                  leading: CircleAvatar(
                    backgroundColor: alert['severity'] == 'Critical' ? Colors.red : (alert['severity'] == 'High' ? Colors.orange : Colors.blue),
                    child: Icon(alert['severity'] == 'Critical' ? Icons.error : Icons.warning, color: Colors.white),
                  ),
                  title: Text(alert['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
                  subtitle: Text('${alert['time']} • ${alert['source']}'),
                  trailing: Chip(
                    label: Text(alert['severity']),
                    backgroundColor: alert['severity'] == 'Critical' ? Colors.red.shade100 : Colors.orange.shade100,
                  ),
                  onTap: () {},
                ),
              )),
              const SizedBox(height: 24),
              const Text(
                'Quick Actions',
                style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 16),
              Row(
                children: [
                  Expanded(
                    child: Card(
                      child: InkWell(
                        onTap: () {},
                        borderRadius: BorderRadius.circular(12),
                        child: const Padding(
                          padding: EdgeInsets.all(16),
                          child: Column(
                            children: [
                              Icon(Icons.search, size: 32, color: Colors.blue),
                              SizedBox(height: 8),
                              Text('Threat Hunting', textAlign: TextAlign.center),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Card(
                      child: InkWell(
                        onTap: () {},
                        borderRadius: BorderRadius.circular(12),
                        child: const Padding(
                          padding: EdgeInsets.all(16),
                          child: Column(
                            children: [
                              Icon(Icons.analytics, size: 32, color: Colors.green),
                              SizedBox(height: 8),
                              Text('SIEM Dashboard', textAlign: TextAlign.center),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: Card(
                      child: InkWell(
                        onTap: () {},
                        borderRadius: BorderRadius.circular(12),
                        child: const Padding(
                          padding: EdgeInsets.all(16),
                          child: Column(
                            children: [
                              Icon(Icons.list_alt, size: 32, color: Colors.orange),
                              SizedBox(height: 8),
                              Text('IOC Management', textAlign: TextAlign.center),
                            ],
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  List<Map<String, dynamic>> _getRecentAlerts() {
    return [
      {'title': 'Suspicious Login Detected', 'severity': 'High', 'time': '5 minutes ago', 'source': '192.168.1.100'},
      {'title': 'Malware Signature Match', 'severity': 'Critical', 'time': '15 minutes ago', 'source': 'Endpoint-01'},
      {'title': 'Unusual Outbound Traffic', 'severity': 'Medium', 'time': '1 hour ago', 'source': 'Firewall'},
      {'title': 'Failed Login Attempts', 'severity': 'Low', 'time': '2 hours ago', 'source': 'Authentication Server'},
    ];
  }
}