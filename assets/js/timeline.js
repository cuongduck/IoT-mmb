// Timeline Component
const ProductionTimeline = ({ plan }) => {
    const today = new Date();
    const dates = [];
    for (let i = -5; i <= 5; i++) {
        const date = new Date(today);
        date.setDate(today.getDate() + i);
        dates.push(date);
    }

    const formatDate = (date) => {
        return `${date.getDate()}/${date.getMonth() + 1}`;
    };

    const getDateStatus = (date, plan) => {
        if (!plan || !plan.Tu_ngay || !plan.den_ngay) {
            return 'no-plan'; // Không có kế hoạch
        }

        const currentDate = new Date(date);
        const startDate = new Date(plan.Tu_ngay);
        const endDate = new Date(plan.den_ngay);
        
        // Reset hours for date comparison
        currentDate.setHours(0, 0, 0, 0);
        startDate.setHours(0, 0, 0, 0);
        endDate.setHours(0, 0, 0, 0);
        
        if (currentDate >= startDate && currentDate <= endDate) {
            // Trong khoảng thời gian kế hoạch
            if (currentDate < today) {
                return 'completed'; // Đã chạy xong
            }
            return 'in-progress'; // Đang chạy hoặc chưa chạy
        }
        
        return 'no-plan'; // Ngoài khoảng thời gian kế hoạch
    };

    const getStatusColor = (status) => {
        switch (status) {
            case 'no-plan':
                return 'bg-red-500';
            case 'completed':
                return 'bg-green-500';
            case 'in-progress':
                return 'bg-blue-500';
            default:
                return 'bg-gray-200';
        }
    };

    const getStatusTitle = (status) => {
        switch (status) {
            case 'no-plan':
                return 'Không có kế hoạch sản xuất';
            case 'completed':
                return 'Đã hoàn thành sản xuất';
            case 'in-progress':
                return 'Trong kế hoạch sản xuất';
            default:
                return '';
        }
    };

    return React.createElement('div', { className: 'mt-4' },
        React.createElement('div', { className: 'flex items-center justify-between' },
            dates.map((date, index) => {
                const isToday = date.getDate() === today.getDate() && 
                               date.getMonth() === today.getMonth();
                const status = getDateStatus(date, plan);
                
                return React.createElement('div', {
                    key: index,
                    className: 'flex flex-col items-center flex-1'
                }, [
                    React.createElement('div', {
                        className: `text-xs mb-1 ${isToday ? 'font-bold' : ''}`,
                        key: 'date'
                    }, formatDate(date)),
                    React.createElement('div', {
                        className: 'w-full px-1',
                        key: 'bar'
                    }, React.createElement('div', {
                        className: `h-4 rounded ${getStatusColor(status)} ${
                            isToday ? 'border-2 border-red-500' : ''
                        }`,
                        title: getStatusTitle(status)
                    }))
                ]);
            })
        )
    );
};

// Hàm khởi tạo timeline cho mỗi line
function initializeTimeline(line, planData) {
    const container = document.getElementById(`timeline-${line}`);
    if (container) {
        try {
            ReactDOM.render(
                React.createElement(ProductionTimeline, { plan: planData }),
                container
            );
        } catch (error) {
            console.error(`Error initializing timeline for ${line}:`, error);
        }
    } else {
        console.error(`Container not found for ${line}`);
    }
}