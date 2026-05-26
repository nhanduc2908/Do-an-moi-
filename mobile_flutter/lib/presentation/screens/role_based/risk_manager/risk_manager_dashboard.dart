import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/auth_provider.dart';
import '../../../widgets/role_based/role_navigation_drawer.dart';
import '../../../widgets/dashboard/statistic_card.dart';

class RiskManagerDashboard extends ConsumerWidget {
  const RiskManagerDashboard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final user = ref.watch(authProvider).user;
    
    return Scaffold(
      drawer: const RoleNavigationDrawer(),
      appBar: AppBar(
        title: const Text('Risk Management Dashboard'),
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
                  colors: [Color(0xFFF39C12), Color(0xFFE67E22)],
                ),
                borderRadius: BorderRadius.circular(16),
              ),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 30,
                    backgroundColor: Colors.white,
                    child: Text(
                      user?.initials ?? 'RM',
                      style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Color(0xFFF39C12)),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Welcome, ${user?.name ?? 'Risk Manager'}',
                          style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          RoleConstants.roleDisplayNames[RoleConstants.riskManager] ?? 'Risk Manager',
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
              'Risk Overview',
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
                  title: 'High Risks',
                  value: '12',
                  icon: Icons.error,
                  color: Colors.red,
                ),
                StatisticCard(
                  title: 'Medium Risks',
                  value: '25',
                  icon: Icons.warning,
                  color: Colors.orange,
                ),
                StatisticCard(
                  title: 'Low Risks',
                  value: '35',
                  icon: Icons.info,
                  color: Colors.green,
                ),
                StatisticCard(
                  title: 'Risk Score',
                  value: '68',
                  icon: Icons.score,
                  color: Colors.blue,
                ),
              ],
            ),
            const SizedBox(height: 24),
            const Text(
              'Risk Matrix',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            Card(
              child: Container(
                height: 200,
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    const Row(
                      mainAxisAlignment: MainAxisAlignment.spaceAround,
                      children: [
                        Text('Impact →', style: TextStyle(fontWeight: FontWeight.bold)),
                        Text('Low', style: TextStyle(fontSize: 12)),
                        Text('Medium', style: TextStyle(fontSize: 12)),
                        Text('High', style: TextStyle(fontSize: 12)),
                      ],
                    ),
                    const SizedBox(height: 8),
                    _buildRiskRow('High', 2, 5, 8),
                    _buildRiskRow('Medium', 5, 10, 8),
                    _buildRiskRow('Low', 10, 8, 3),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),
            const Text(
              'Top Risks',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            ..._getTopRisks().map((risk) => Card(
              margin: const EdgeInsets.only(bottom: 12),
              child: ListTile(
                leading: CircleAvatar(
                  backgroundColor: risk['severity'] == 'High' ? Colors.red : (risk['severity'] == 'Medium' ? Colors.orange : Colors.green),
                  child: Text(risk['severity'].substring(0, 1), style: const TextStyle(color: Colors.white)),
                ),
                title: Text(risk['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                subtitle: Text('Score: ${risk['score']} • Likelihood: ${risk['likelihood']} • Impact: ${risk['impact']}'),
                trailing: const Icon(Icons.chevron_right),
                onTap: () {},
              ),
            )),
          ],
        ),
      ),
    );
  }

  Widget _buildRiskRow(String label, int low, int medium, int high) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          SizedBox(width: 60, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold))),
          Expanded(
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _buildRiskCell(low, Colors.green),
                _buildRiskCell(medium, Colors.orange),
                _buildRiskCell(high, Colors.red),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildRiskCell(int value, Color color) {
    return Container(
      width: 50,
      height: 50,
      decoration: BoxDecoration(
        color: color.withOpacity(value > 0 ? 0.8 : 0.2),
        borderRadius: BorderRadius.circular(8),
      ),
      child: Center(
        child: Text(
          value.toString(),
          style: TextStyle(color: value > 0 ? Colors.white : Colors.grey, fontWeight: FontWeight.bold),
        ),
      ),
    );
  }

  List<Map<String, dynamic>> _getTopRisks() {
    return [
      {'name': 'Data Breach Risk', 'severity': 'High', 'score': 25, 'likelihood': 5, 'impact': 5},
      {'name': 'Ransomware Attack', 'severity': 'High', 'score': 20, 'likelihood': 4, 'impact': 5},
      {'name': 'Compliance Violation', 'severity': 'Medium', 'score': 12, 'likelihood': 3, 'impact': 4},
      {'name': 'Third-party Risk', 'severity': 'Medium', 'score': 9, 'likelihood': 3, 'impact': 3},
    ];
  }
}